<?php

namespace App\Services;

use App\Mail\InvitationMail;
use App\Models\InvitationToken;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class InvitationService
{
    /**
     * Create an invitation token for a new user after purchase.
     */
    public function createInvitation(Order $order): InvitationToken
    {
        $expiryDays = config('nativemeta.invitation_expiry_days', 7);

        $token = InvitationToken::create([
            'email' => $order->email,
            'order_id' => $order->id,
            'expires_at' => now()->addDays($expiryDays),
        ]);

        // Send invitation email
        Mail::to($order->email)->queue(new InvitationMail($token, $order));

        activity()
            ->performedOn($token)
            ->withProperties([
                'email' => $order->email,
                'order_id' => $order->id,
                'expires_at' => $token->expires_at->toDateTimeString(),
            ])
            ->log('Token d\'invitation envoyé');

        return $token;
    }

    /**
     * Validate an invitation token.
     */
    public function validateToken(string $tokenString): ?InvitationToken
    {
        return InvitationToken::where('token', $tokenString)
            ->valid()
            ->first();
    }

    /**
     * Complete registration via invitation token.
     * Creates the user, links order, grants course access.
     */
    public function completeRegistration(InvitationToken $token, array $userData): User
    {
        $user = User::create([
            'name' => $userData['name'],
            'email' => $token->email,
            'password' => bcrypt($userData['password']),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Assign student role
        $user->assignRole('student');

        // Mark token as used
        $token->markAsUsed();

        // Link order to user and grant access
        $order = $token->order;
        $order->update(['user_id' => $user->id]);

        $enrollmentService = app(EnrollmentService::class);
        foreach ($order->items as $item) {
            $enrollmentService->grantAccess($user, $item->course, $order);
        }

        // Update Stripe customer ID if available
        if ($order->metadata && isset($order->metadata['stripe_customer_id'])) {
            $user->update(['stripe_customer_id' => $order->metadata['stripe_customer_id']]);
        }

        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties(['via' => 'invitation', 'order_id' => $order->id])
            ->log('Inscription via invitation');

        return $user;
    }

    /**
     * Resend an invitation email.
     */
    public function resendInvitation(InvitationToken $token): void
    {
        if ($token->isUsed()) {
            throw new \Exception('Ce token a déjà été utilisé.');
        }

        // Extend expiry
        $expiryDays = config('nativemeta.invitation_expiry_days', 7);
        $token->update(['expires_at' => now()->addDays($expiryDays)]);

        Mail::to($token->email)->queue(new InvitationMail($token, $token->order));
    }

    /**
     * Clean up expired tokens.
     */
    public function cleanExpiredTokens(): int
    {
        return InvitationToken::expired()
            ->whereNull('used_at')
            ->delete();
    }
}
