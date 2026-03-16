<?php

namespace App\Services;

use App\Mail\InvitationMail;
use App\Mail\PurchaseConfirmationMail;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EnrollmentService
{
    /**
     * Grant access to a course for a user.
     */
    public function grantAccess(User $user, Course $course, ?Order $order = null, ?\DateTimeInterface $expiresAt = null): Enrollment
    {
        $enrollment = Enrollment::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'order_id' => $order?->id,
                'enrolled_at' => now(),
                'expires_at' => $expiresAt,
            ]
        );

        activity()
            ->causedBy(auth()->user() ?? $user)
            ->performedOn($enrollment)
            ->withProperties([
                'course' => $course->title,
                'user' => $user->email,
            ])
            ->log('Accès formation accordé');

        return $enrollment;
    }

    /**
     * Revoke access to a course for a user.
     */
    public function revokeAccess(User $user, Course $course): void
    {
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment) {
            $enrollment->delete();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($enrollment)
                ->withProperties([
                    'course' => $course->title,
                    'user' => $user->email,
                ])
                ->log('Accès formation révoqué');
        }
    }

    /**
     * Process enrollment after a successful payment.
     * If the user exists, grant access immediately.
     * If no user account, the InvitationService will handle token generation.
     */
    public function processAfterPayment(Order $order): void
    {
        try {
            Log::info('Processing enrollment after payment', ['order_id' => $order->id, 'email' => $order->email]);
            
            $user = User::where('email', $order->email)->first();
            Log::info('User lookup result', ['email' => $order->email, 'user_exists' => $user ? 'yes' : 'no']);

            if ($user) {
                // Existing user: grant access and link order
                Log::info('Processing existing user', ['user_id' => $user->id]);
                $order->update(['user_id' => $user->id]);

                foreach ($order->items as $item) {
                    $this->grantAccess($user, $item->course, $order);
                    Log::info('Access granted', ['user_id' => $user->id, 'course_id' => $item->course->id]);
                }

                // Send confirmation email
                Log::info('Sending purchase confirmation email to: ' . $user->email);
                
                try {
                    Mail::to($user->email)->queue(new PurchaseConfirmationMail($order));
                    Log::info('Purchase confirmation email queued for: ' . $user->email);
                } catch (\Exception $mailException) {
                    Log::error('Failed to queue purchase confirmation email', [
                        'email' => $user->email,
                        'error' => $mailException->getMessage(),
                    ]);
                }
            } else {
                // New user: create invitation token
                Log::info('Creating invitation for new user: ' . $order->email);
                
                try {
                    $token = app(InvitationService::class)->createInvitation($order);
                    Log::info('Invitation created for: ' . $order->email, ['token_id' => $token->id]);
                } catch (\Exception $invitationException) {
                    Log::error('Failed to create invitation', [
                        'email' => $order->email,
                        'error' => $invitationException->getMessage(),
                    ]);
                }
            }
            
            Log::info('Enrollment processing completed', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            Log::error('Error in processAfterPayment: ' . $e->getMessage());
            Log::error('Order ID: ' . $order->id . ', Email: ' . $order->email);
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Extend enrollment expiry for a user.
     */
    public function extendAccess(User $user, Course $course, \DateTimeInterface $newExpiresAt): ?Enrollment
    {
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment) {
            $enrollment->update(['expires_at' => $newExpiresAt]);
            return $enrollment;
        }

        return null;
    }
}
