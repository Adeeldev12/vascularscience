<?php

namespace App\Mail;

use App\Models\Scientist;
use App\Models\Availability;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScientistAvailabilityNotification extends Mailable
{
    use Queueable, SerializesModels;

    // /**
    //  * Create a new message instance.
    //  */
    // public function __construct()
    // {
    //     //
    // }

    // /**
    //  * Get the message envelope.
    //  */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Scientist Availability Notification',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         markdown: 'emails.availability.notification',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }

//    public $scientist;
//     public $availability;
//     public $action;

//     /**
//      * Create a new message instance.
//      */
//     public function __construct($scientist, $availability, $action)
//     {
//         $this->scientist = $scientist;
//         $this->availability = $availability;
//         $this->action = $action;
//     }

//     /**
//      * Get the message envelope.
//      */
//    public function envelope(): Envelope
// {
//     $admin = config('mail.admin_address') ?: env('MAIL_ADMIN_ADDRESS') ?: 'adeeldev12@gmail.com';

//     return new Envelope(
//         from: new \Illuminate\Mail\Mailables\Address(
//             config('mail.from.address') ?? env('MAIL_FROM_ADDRESS') ?? 'notifications@vascularscience.test',
//             config('mail.from.name') ?? env('MAIL_FROM_NAME') ?? 'Vascular Science'
//         ),
//         to: [ new \Illuminate\Mail\Mailables\Address($admin, 'Admin') ],
//         subject: 'Scientist Availability ' . ucfirst($this->action),
//     );
// }



//     /**
//      * Get the message content definition.
//      */
//     public function content(): Content
//     {
//         return new Content(
//             markdown: 'emails.availability.notification',
//             with: [
//                 'scientist' => $this->scientist,
//                 'availability' => $this->availability,
//                 'action' => $this->action,
//             ],
//         );
//     }

//     /**
//      * Get the attachments for the message.
//      */
//     public function attachments(): array
//     {
//         return [];
//     }

public $scientist;
    public $availability;
    public $action;

    public function __construct($scientist, $availability, $action)
    {
        $this->scientist = $scientist;
        $this->availability = $availability;
        $this->action = $action;
    }

    public function build()
    {
        return $this->subject('Scientist Availability ' . ucfirst($this->action))
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.scientist_availability_notification');
    }
}
