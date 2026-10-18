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
        $name = config('storefront.store_name', 'BENAZIR');

        $subject = match ($this->notificationType) {
            'new_order' => "New {$name} order: {$this->order->order_number}",
            'placed' => "We received your {$name} order {$this->order->order_number}",
            'confirmed' => "Your {$name} order is confirmed",
            'shipped' => "Your {$name} order has shipped",
            'delivered' => "Your {$name} order has been delivered",
            'cancelled' => "Your {$name} order has been cancelled",
            default => "Update on {$name} order {$this->order->order_number}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.orders.notification');
    }
}
