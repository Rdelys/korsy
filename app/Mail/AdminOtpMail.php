<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AdminOtpMail extends Mailable
{
    public function __construct(
        public string $code,
        public ?string $ip = null,
        public ?string $device = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Code de connexion administrateur — Tafely Kôrsy');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin-otp');
    }
}