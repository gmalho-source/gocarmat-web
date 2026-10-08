<?php

namespace App\Mail;

use App\Models\GasOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GasOrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public GasOrder $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recebemos o seu pedido de gás — QSCMC',
            from: new Address(config('mail.from.address'), 'QSCMC'),
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.gas-order-confirmation');
    }
}
