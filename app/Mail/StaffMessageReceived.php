<?php

namespace App\Mail;

use App\Models\StaffMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public StaffMessage $message) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address', 'noreply@futhlafia.gov.ng'),
            subject: 'New staff message: ' . ($this->message->subject ?: '(no subject)'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.staff-message-received',
            with: [
                'staffMessage' => $this->message,
                'inboxUrl'     => url('/admin/staff-messages'),
            ],
        );
    }
}
