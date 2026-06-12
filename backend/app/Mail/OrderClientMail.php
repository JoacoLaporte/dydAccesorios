<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $data,
        public string $numeroPedido
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Confirmación de pedido Nº {$this->numeroPedido} — DYD Accesorios",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.order-client',
        );
    }
}
