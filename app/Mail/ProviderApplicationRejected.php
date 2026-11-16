<?php

namespace App\Mail;

use App\Models\ProviderApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProviderApplicationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ProviderApplication $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address', 'noreply@futhlafia.gov.ng'),
            subject: 'Update on your FUTH Lafia provider application',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.provider-application-rejected',
            with: [
                'application' => $this->application,
            ],
        );
    }
}
