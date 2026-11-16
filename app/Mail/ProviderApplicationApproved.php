<?php

namespace App\Mail;

use App\Models\ProviderApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProviderApplicationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ProviderApplication $application, public ?string $loginUrl = null)
    {
        $this->loginUrl = $loginUrl ?? route('filament.admin.auth.login');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address', 'noreply@futhlafia.gov.ng'),
            subject: 'Your FUTH Lafia provider application has been approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.provider-application-approved',
            with: [
                'application' => $this->application,
                'loginUrl'    => $this->loginUrl,
                'accountType' => ucfirst($this->application->account_type),
            ],
        );
    }
}
