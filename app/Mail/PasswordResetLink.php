<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetLink extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $email,
        public string $token,
        public string $resetUrl,
        public string $userType = 'staff', // 'staff' or 'patient'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address', 'noreply@futhlafia.gov.ng'),
            subject: $this->userType === 'patient'
                ? 'Reset your FUTH Lafia patient portal password'
                : 'Reset your FUTH Lafia EMR staff password',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-link',
            with: [
                'resetUrl' => $this->resetUrl,
                'userType' => $this->userType,
                'expiresMinutes' => config('auth.passwords.'
                    . ($this->userType === 'patient' ? 'patient_portal_users' : 'users')
                    . '.expire', 60),
            ],
        );
    }
}
