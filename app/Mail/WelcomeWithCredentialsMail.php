<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeWithCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $password,
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Bienvenue sur NativeMeta - Vos identifiants de connexion',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-with-credentials',
            with: [
                'user' => $this->user,
                'password' => $this->password,
                'order' => $this->order,
                'loginUrl' => route('login'),
            ],
        );
    }
}
