<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderProcessedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $estimatedCompletion;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $estimatedCompletion = null)
    {
        $this->order = $order;
        $this->estimatedCompletion = $estimatedCompletion;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesanan Anda Sedang Diproses',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-processed',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
