<?php

namespace App\Mail;

use App\Models\Availability;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class AvailabilityNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $availability;
    public $action;

    /**
     * Create a new message instance.
     */
    public function __construct(Availability $availability, string $action)
    {
        $this->availability = $availability;
        $this->action = $action;
    }

    /**
     * Define the message envelope (headers like from, to, subject).
     */
    public function envelope(): Envelope
{
    return new Envelope(
        from: new Address(
            config('mail.from.address', 'no-reply@vascularscience.co.uk'),
            config('mail.from.name', 'Vascular Science')
        ),
        to: [
            new Address(
                config('mail.admin_address', 'thevascularscience@gmail.com'),
                'Admin'
            ),
        ],
        subject: "Scientist Availability " . ucfirst($this->action) . " by " . optional($this->availability->scientist)->name,
    );
}


    /**
     * Define the email content.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.availability-notification',
            with: [
                'availability' => $this->availability,
                'action' => $this->action,
                'scientist' => $this->availability->scientist,
            ],
        );
    }

    /**
     * Define attachments (none for now).
     */
    public function attachments(): array
    {
        return [];
    }
}
