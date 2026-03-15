<?php

namespace App\Mail;

use App\Models\InvitationToken;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly InvitationToken $token,
        public readonly Order $order
    ) {}

    public function envelope(): Envelope
    {
        $courseName = $this->order->items->first()?->course?->title ?? 'votre formation';
        return new Envelope(
            subject: "🎉 Accède à ta formation {$courseName} - NativeMeta",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invitation',
            with: [
                'registerUrl' => url('/register?token=' . $this->token->token),
                'courseName' => $this->order->items->first()?->course?->title ?? 'votre formation',
                'expiresAt' => $this->token->expires_at->format('d/m/Y à H:i'),
            ],
        );
    }
}
