<?php

namespace App\Services;

use App\Mail\OrderNotificationMail;
use App\Models\Admin;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OrderNotificationService
{
    public function orderPlaced(Order $order): void
    {
        $this->queueTo($order->customer_email, $order, 'placed');

        $adminEmail = config('storefront.notifications.admin_email');

        if (is_string($adminEmail) && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            $this->queueTo($adminEmail, $order, 'new_order');
        }

        $this->notifyAdmins($order);
    }

    /**
     * Post one in-app notification to every active admin so the new order
     * surfaces in the Admin → Settings → Orders & Notifications panel.
     */
    private function notifyAdmins(Order $order): void
    {
        foreach (Admin::where('status', 'active')->get() as $admin) {
            $admin->notifications()->create([
                'type' => 'new_order',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'customer_email' => $order->customer_email,
                    'grand_total' => (float) $order->grand_total,
                    'currency' => $order->currency,
                ],
            ]);
        }
    }

    public function statusChanged(Order $order, string $status): void
    {
        if (! in_array($status, [
            Order::STATUS_CONFIRMED,
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED,
            Order::STATUS_CANCELLED,
        ], true)) {
            return;
        }

        $this->queueTo($order->customer_email, $order, $status);
    }

    private function queueTo(?string $recipient, Order $order, string $notificationType): void
    {
        if (! is_string($recipient) || ! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        try {
            Mail::to($recipient)->queue(new OrderNotificationMail($order, $notificationType));
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
