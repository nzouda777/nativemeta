<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Services\EnrollmentService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function __construct(
        private readonly StripeService $stripeService,
        private readonly EnrollmentService $enrollmentService
    ) {}

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = \App\Models\Setting::get('stripe_webhook_secret', config('services.stripe.webhook_secret'));

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);
            abort(400, 'Invalid webhook signature');
        } catch (\Exception $e) {
            Log::error('Stripe webhook error', ['error' => $e->getMessage()]);
            abort(400, 'Webhook error');
        }

        Log::info('Stripe webhook received', ['type' => $event->type]);

        match($event->type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($event->data->object),
            'payment_intent.payment_failed' => $this->handlePaymentFailed($event->data->object),
            'charge.refunded' => $this->handleRefund($event->data->object),
            default => Log::info('Unhandled Stripe event', ['type' => $event->type]),
        };

        return response()->json(['received' => true]);
    }

    private function handleCheckoutCompleted($session): void
    {
        try {
            Log::info('Processing checkout completed', ['session_id' => $session->id]);
            
            $order = $this->stripeService->createOrderFromSession($session);
            Log::info('Order created', ['order_id' => $order->id, 'email' => $order->email]);
            
            $this->enrollmentService->processAfterPayment($order);
            Log::info('Enrollment processed', ['order_id' => $order->id]);

            activity()
                ->performedOn($order)
                ->withProperties([
                    'amount' => $order->amount,
                    'email' => $order->email,
                    'stripe_session_id' => $session->id,
                ])
                ->log('Paiement Stripe complété');
                
            Log::info('Checkout completed successfully', ['session_id' => $session->id]);
        } catch (\Exception $e) {
            Log::error('Error processing checkout', [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    private function handlePaymentFailed($paymentIntent): void
    {
        Log::warning('Stripe payment failed', [
            'payment_intent_id' => $paymentIntent->id,
            'error' => $paymentIntent->last_payment_error?->message ?? 'Unknown error',
        ]);

        activity()
            ->withProperties([
                'payment_intent_id' => $paymentIntent->id,
                'error' => $paymentIntent->last_payment_error?->message,
            ])
            ->log('Paiement Stripe échoué');
    }

    private function handleRefund($charge): void
    {
        $order = \App\Models\Order::where('stripe_payment_intent_id', $charge->payment_intent)->first();

        if ($order) {
            $order->update(['status' => 'refunded']);

            activity()
                ->performedOn($order)
                ->withProperties([
                    'amount_refunded' => $charge->amount_refunded / 100,
                    'charge_id' => $charge->id,
                ])
                ->log('Remboursement Stripe traité');
        }
    }
}
