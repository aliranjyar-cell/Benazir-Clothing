<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $notificationType
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->notificationType) {
            'new_order' => "New BENAZIR order: {$this->order->order_number}",
            'placed' => "We received your BENAZIR order {$this->order->order_number}",
            'confirmed' => 'Your BENAZIR order is confirmed',
            'shipped' => 'Your BENAZIR order has shipped',
            'delivered' => 'Your BENAZIR order has been delivered',
            'cancelled' => 'Your BENAZIR order has been cancelled',
            default => "Update on BENAZIR order {$this->order->order_number}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.orders.notification');
    }
}
