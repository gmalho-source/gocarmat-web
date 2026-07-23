<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nova marcação no site — '.$this->booking->name.' ('.$this->booking->service.')',
            replyTo: [$this->booking->email],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.booking-notification');
    }
}
