<?php
namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderWorkflowService
{
    /**
     * Change an order's fulfillment status safely.
     */
    public function changeStatus(
        int $orderId,
        string $newStatus,
        ?string $note = null
    ): Order {
        if (! in_array($newStatus, Order::STATUSES, true)) {
            throw ValidationException::withMessages([
                'status' => 'The selected order status is invalid.',
            ]);
        }

        return DB::transaction(function () use (
            $orderId,
            $newStatus,
            $note
        ) {
            $order = Order::query()
                ->lockForUpdate()
                ->findOrFail($orderId);

            $currentStatus = $order->status;

            if ($currentStatus === $newStatus) {
                throw ValidationException::withMessages([
                    'status' => 'The order is already in this status.',
                ]);
            }

            $allowedTransitions = [
                Order::STATUS_PENDING => [
                    Order::STATUS_CONFIRMED,
                    Order::STATUS_CANCELLED,
                ],
                Order::STATUS_CONFIRMED => [
                    Order::STATUS_PROCESSING,
                    Order::STATUS_CANCELLED,
                ],
                Order::STATUS_PROCESSING => [
                    Order::STATUS_SHIPPED,
                    Order::STATUS_CANCELLED,
                ],
                Order::STATUS_SHIPPED => [
                    Order::STATUS_DELIVERED,
                ],
                Order::STATUS_DELIVERED => [],
                Order::STATUS_CANCELLED => [],
            ];

            if (! in_array(
                $newStatus,
                $allowedTransitions[$currentStatus] ?? [],
                true
            )) {
                throw ValidationException::withMessages([
                    'status' => "An order cannot move from {$currentStatus} to {$newStatus}.",
                ]);
            }

            if ($newStatus === Order::STATUS_CONFIRMED) {
                $this->deductStock($order);
                $order->confirmed_at = now();
            }

            if ($newStatus === Order::STATUS_SHIPPED) {
                $order->shipped_at = now();
            }

            if ($newStatus === Order::STATUS_DELIVERED) {
                $order->delivered_at = now();
            }

            if ($newStatus === Order::STATUS_CANCELLED) {
                $this->restoreStockIfDeducted($order);

                $order->cancelled_at = now();
                $order->cancellation_reason = $note;
            }

            $order->status = $newStatus;
            $order->save();

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'admin_id' => Auth::guard('admin')->id(),
                'from_status' => $currentStatus,
                'to_status' => $newStatus,
                'note' => $note,
                'created_at' => now(),
            ]);

            return $order->fresh([
                'items',
                'statusHistories.admin',
            ]);
        }, 3);
    }

    /**
     * Deduct all order quantities once, when the order is confirmed.
     */
    private function deductStock(Order $order): void
    {
        if ($order->stock_deducted_at !== null) {
            throw ValidationException::withMessages([
                'status' => 'Stock has already been deducted for this order.',
            ]);
        }

        $quantities = $order->items()
            ->selectRaw('product_variant_id, SUM(quantity) as total_quantity')
            ->groupBy('product_variant_id')
            ->orderBy('product_variant_id')
            ->get();

        if ($quantities->isEmpty()) {
            throw ValidationException::withMessages([
                'status' => 'This order has no items to confirm.',
            ]);
        }

        foreach ($quantities as $item) {
            if (! $item->product_variant_id) {
                throw ValidationException::withMessages([
                    'status' => 'An order item no longer has a linked product variant. Stock cannot be verified.',
                ]);
            }

            $variant = ProductVariant::query()
                ->lockForUpdate()
                ->find($item->product_variant_id);

            if (! $variant || ! $variant->is_active) {
                throw ValidationException::withMessages([
                    'status' => 'A product variant is unavailable. Review the order before confirming it.',
                ]);
            }

            $quantity = (int) $item->total_quantity;

            if ($quantity < 1 || $variant->stock_quantity < $quantity) {
                throw ValidationException::withMessages([
                    'status' => "Insufficient stock for SKU {$variant->sku}. Available: {$variant->stock_quantity}; required: {$quantity}.",
                ]);
            }

            $variant->decrement('stock_quantity', $quantity);
        }

        $order->stock_deducted_at = now();
    }

    /**
     * Restore stock only when this order previously deducted it.
     */
    private function restoreStockIfDeducted(Order $order): void
    {
        if ($order->stock_deducted_at === null) {
            return;
        }

        $quantities = $order->items()
            ->selectRaw('product_variant_id, SUM(quantity) as total_quantity')
            ->groupBy('product_variant_id')
            ->orderBy('product_variant_id')
            ->get();

        foreach ($quantities as $item) {
            if (! $item->product_variant_id) {
                throw ValidationException::withMessages([
                    'status' => 'A product variant linked to this deducted order is missing. Resolve inventory manually before cancelling.',
                ]);
            }

            $variant = ProductVariant::query()
                ->lockForUpdate()
                ->find($item->product_variant_id);

            if (! $variant) {
                throw ValidationException::withMessages([
                    'status' => 'A product variant linked to this deducted order is missing. Resolve inventory manually before cancelling.',
                ]);
            }

            $variant->increment(
                'stock_quantity',
                (int) $item->total_quantity
            );
        }

        // Clear the marker only after all stock has been restored.
        $order->stock_deducted_at = null;
    }
}

