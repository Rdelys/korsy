<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $code  Code en clair à 6 chiffres (jamais stocké tel quel).
     * @param  string|null  $deviceLabel  Navigateur / appareil à l'origine de la demande, pour rassurer l'utilisateur.
     */
    public function __construct(
        public User $user,
        public string $code,
        public ?string $deviceLabel = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre code de vérification Tafely Kôrsy : '.$this->code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.login-otp',
            with: [
                'name' => $this->user->name,
                'code' => $this->code,
                'ttlMinutes' => \App\Models\LoginOtp::TTL_MINUTES,
                'deviceLabel' => $this->deviceLabel,
            ],
        );
    }
}