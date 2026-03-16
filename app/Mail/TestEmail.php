<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🧪 Email de test - NativeMeta',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.test-email',
            with: [
                'appName' => config('app.name'),
                'testTime' => now()->format('d/m/Y H:i:s'),
                'mailer' => config('mail.default'),
            ],
        );
    }
}
