<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Stripe\StripeClient;

class StripeService
{
    /**
     * Get the active Stripe secret key based on settings or env.
     */
    public static function getSecretKey(): string
    {
        $mode = Setting::get('stripe_mode', config('services.stripe.mode', 'test'));
        $key = $mode === 'live' 
            ? Setting::get('stripe_live_secret_key', config('services.stripe.live_secret'))
            : Setting::get('stripe_test_secret_key', config('services.stripe.test_secret'));

        if (empty($key)) {
            throw new \Exception("Stripe Secret Key is missing. Please configure it in .env or settings.");
        }

        return $key;
    }

    /**
     * Get the active Stripe public key based on settings or env.
     */
    public static function getPublicKey(): string
    {
        $mode = Setting::get('stripe_mode', config('services.stripe.mode', 'test'));
        $key = $mode === 'live'
            ? Setting::get('stripe_live_public_key', config('services.stripe.live_key'))
            : Setting::get('stripe_test_public_key', config('services.stripe.test_key'));

        if (empty($key)) {
            throw new \Exception("Stripe Public Key is missing. Please configure it in .env or settings.");
        }

        return $key;
    }

    /**
     * Get a Stripe client instance with the correct key.
     */
    public function getClient(): StripeClient
    {
        return new StripeClient(self::getSecretKey());
    }

    /**
     * Create a Checkout Session for a course.
     */
    public function createCheckoutSession(Course $course, string $email, ?string $customerId = null)
    {
        $client = $this->getClient();

        return $client->checkout->sessions->create([
            'customer' => $customerId,
            'customer_email' => $customerId ? null : $email,
            'line_items' => [[
                'price_data' => [
                    'currency' => self::getCurrency(),
                    'product_data' => [
                        'name' => $course->title,
                        'images' => $course->thumbnail ? [asset('storage/' . $course->thumbnail)] : [],
                        'description' => $course->description,
                    ],
                    'unit_amount' => (int) ($course->getEffectivePrice() * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            'metadata' => [
                'course_id' => $course->id,
                'user_email' => $email,
            ],
        ]);
    }

    /**
     * Retrieve a Checkout Session.
     */
    public function retrieveSession(string $sessionId)
    {
        return $this->getClient()->checkout->sessions->retrieve($sessionId);
    }

    /**
     * Create an order from a Stripe session.
     */
    public function createOrderFromSession($session): Order
    {
        $user = User::where('email', $session->metadata->user_email)->first();
        
        $order = Order::create([
            'user_id' => $user?->id,
            'email' => $session->metadata->user_email,
            'amount' => $session->amount_total / 100,
            'status' => 'paid',
            'stripe_session_id' => $session->id,
            'stripe_payment_intent_id' => $session->payment_intent,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'course_id' => $session->metadata->course_id,
            'price' => $session->amount_total / 100,
        ]);

        if ($user && $session->customer) {
            $user->update(['stripe_customer_id' => $session->customer]);
        }

        return $order;
    }

    /**
     * Get the active currency.
     */
    public static function getCurrency(): string
    {
        return Setting::get('stripe_currency', config('services.stripe.currency', 'eur'));
    }
}
