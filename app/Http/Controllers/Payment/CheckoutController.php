<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly StripeService $stripeService
    ) {}

    public function create(Request $request, Course $course)
    {
        // Only validate email for guest users
        if (!auth()->check()) {
            $request->validate([
                'email' => ['required', 'email'],
            ], [
                'email.required' => 'L\'adresse email est requise pour activer ton accès.',
                'email.email' => 'L\'adresse email doit être valide.',
            ]);
        }

        $email = auth()->check() ? auth()->user()->email : $request->input('email');
        
        // Get or create Stripe customer ID
        if (auth()->check()) {
            $customerId = auth()->user()->stripe_customer_id;
            \Log::info('Logged in user checkout', ['email' => $email, 'existing_customer_id' => $customerId]);
        } else {
            // Create Stripe customer for guest checkout
            try {
                \Stripe\Stripe::setApiKey(\App\Models\Setting::get('stripe_test_secret_key'));
                $stripeCustomer = \Stripe\Customer::create([
                    'email' => $email,
                    'name' => str($email)->before('@')->limit(6)->toString(),
                ]);
                $customerId = $stripeCustomer->id;
                \Log::info('Created new Stripe customer', ['email' => $email, 'new_customer_id' => $customerId]);
            } catch (\Exception $e) {
                \Log::error('Failed to create Stripe customer', ['email' => $email, 'error' => $e->getMessage()]);
                $customerId = null;
            }
        }

        $user_password = Str::random(6);
        
        

        try {
            \Log::info('Initiating checkout for course: ' . $course->id . ' | Email: ' . $email . ' | Customer ID: ' . ($customerId ?? 'none'));
            
            $session = $this->stripeService->createCheckoutSession($course, $email, $customerId);
            \Log::info('Stripe session created: ' . $session->id . ' | Mode: ' . (config('services.stripe.mode') ?? 'test'));
            // \Log::info('Session INFO: ' . $session);
            \Log::info('Checkout for course initiated: ' . $course->id . ' | Email: ' . $email . ' | Customer ID: ' . ($customerId ?? 'none'));
            
            User::create([
            'name' => str($email)->before('@')->limit(6)->toString(),
            'email' => $email,
            'password' => bcrypt($user_password),
            'stripe_customer_id' => $session->customer,
        ]);

        Order::create([
            'user_id' => User::where('email', $email)->first()->id,
            'course_id' => $course->id,
            'email' => User::where('email', $email)->first()->email,
            'amount' => $session->amount_total / 100,
            'currency' => $session->currency,
            'stripe_session_id' => $session->id,
            'stripe_payment_intent_id' => $session->payment_intent,
            'status' => 'paid',
        ]);

        OrderItem::create([
            'order_id' => Order::where('stripe_session_id', $session->id)->first()->id,
            'course_id' => $course->id,
            'user_id' => User::where('email', $email)->first()->id,
            'amount' => $session->amount_total / 100,
            'currency' => $session->currency,
            'stripe_session_id' => $session->id,
            'stripe_payment_intent_id' => $session->payment_intent,
            'status' => 'paid',
        ]);

            return Inertia::location($session->url);
        } catch (\Exception $e) {
            \Log::error('Checkout Error: ' . $e->getMessage());
            \Log::error('Stripe Mode: ' . (config('services.stripe.mode') ?? 'test'));
            \Log::error('Has Customer ID: ' . ($customerId ? 'yes' : 'no'));
            
            return back()->with('error', 'Erreur lors de la création du paiement : ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect('/')->with('error', 'Session de paiement invalide.');
        }

        try {
            $session = $this->stripeService->retrieveSession($sessionId);
            // \Log::info('Session INFO: ' . $session);
            if ($session->payment_status === 'paid') {
                $user = User::where('email', $session->customer_details->email)->first();
                \Log::info('User found: ' . $user->email);
                $order = Order::where('stripe_session_id', $sessionId)->first();
                $orderItem = OrderItem::where('order_id', $order->id)->first();
                \Log::info('Order found: ' . $order->id);
                if ($user) {
                    \Log::info('Sending welcome email with credentials to: ' . $user->email);
                    // Generate password for the user
                    $password = Str::random(12) . '!' . rand(10, 99);

                    // update current user password
                    $user->password = bcrypt($password);
                    $user->save();
                    \Log::info('Password: ' . $password);
                    \Log::info('Order Item: ' . $order);
                    // send welcome email with credentials
                    try {
                        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\WelcomeWithCredentialsMail($user, $password, $order));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send welcome email: ' . $e->getMessage());
                    }
                }
                // else{
                //     // send purchase confirmation email
                //     \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\PurchaseConfirmationMail($orderItem));
                // }
                
            }


            return Inertia::render('Public/CheckoutSuccess', [
                'session' => [
                    'customer_email' => $session->customer_details->email ?? $session->customer_email,
                    'amount_total' => $session->amount_total / 100,
                    'currency' => strtoupper($session->currency),
                ],
            ]);
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Impossible de vérifier le paiement.');
        }
    }

    public function cancel()
    {
        return Inertia::render('Public/CheckoutCancel');
    }
}
