<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\StripeService;
use Illuminate\Http\Request;
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
        $customerId = auth()->user()?->stripe_customer_id;

        try {
            \Log::info('Initiating checkout for course: ' . $course->id . ' | Email: ' . $email);
            $session = $this->stripeService->createCheckoutSession($course, $email, $customerId);
            \Log::info('Stripe session created: ' . $session->id);

            return Inertia::location($session->url);
        } catch (\Exception $e) {
            \Log::error('Checkout Error: ' . $e->getMessage());
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
