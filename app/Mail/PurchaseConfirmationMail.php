<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Accès confirmé : ' . ($this->order->items->first()?->course?->title ?? 'votre formation'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.purchase-confirmation',
            with: [
                'courseName' => $this->order->items->first()?->course?->title ?? 'votre formation',
                'amount' => $this->order->getFormattedAmount(),
                'dashboardUrl' => url('/dashboard'),
            ],
        );
    }
}
