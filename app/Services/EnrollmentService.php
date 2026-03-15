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
        $user = User::where('email', $order->email)->first();

        if ($user) {
            // Existing user: grant access and link order
            $order->update(['user_id' => $user->id]);

            foreach ($order->items as $item) {
                $this->grantAccess($user, $item->course, $order);
            }

            // Send confirmation email
            Mail::to($user->email)->queue(new PurchaseConfirmationMail($order));
        } else {
            // New user: create invitation token
            app(InvitationService::class)->createInvitation($order);
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
