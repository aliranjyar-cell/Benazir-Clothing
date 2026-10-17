<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $notificationType === 'new_order' ? 'New order' : 'Order update' }} | BENAZIR</title>
</head>

<body style="margin:0; padding:0; background:#f5f2ed; color:#282522; font-family:Georgia, 'Times New Roman', serif;">
    @php
        $headings = [
            'new_order' => 'A new order has arrived',
            'placed' => 'Your order is with us',
            'confirmed' => 'Your order is confirmed',
            'shipped' => 'Your order is on its way',
            'delivered' => 'Your order has been delivered',
            'cancelled' => 'Your order has been cancelled',
        ];
        $heading = $headings[$notificationType] ?? 'An update on your order';
        $paymentMethods = config('storefront.checkout.payment_methods', []);
    @endphp
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f2ed; padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                    style="max-width:640px; background:#fff; border-top:4px solid #7a263a;">
                    <tr>
                        <td style="padding:34px 36px 24px; text-align:center; border-bottom:1px solid #e8e3dc;">
                            <p
                                style="margin:0; color:#7a263a; font:600 13px/1.4 Arial, sans-serif; letter-spacing:3px;">
                                BENAZIR</p>
                            <p
                                style="margin:8px 0 0; color:#817a72; font:11px/1.5 Arial, sans-serif; letter-spacing:1.5px; text-transform:uppercase;">
                                Punjabi Couture</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px 36px 12px;">
                            <p
                                style="margin:0 0 12px; color:#7a263a; font:11px/1.5 Arial, sans-serif; letter-spacing:1.4px; text-transform:uppercase;">
                                Order {{ $order->order_number }}</p>
                            <h1
                                style="margin:0 0 14px; color:#282522; font:normal 30px/1.2 Georgia, 'Times New Roman', serif;">
                                {{ $heading }}</h1>
                            <p style="margin:0; color:#625d57; font:15px/1.7 Arial, sans-serif;">
                                @if ($notificationType === 'new_order')
                                    A customer has placed an order. Review the details below to prepare it for
                                    fulfilment.
                                @elseif ($notificationType === 'placed')
                                    Thank you, {{ $order->customer_name }}. We have received your order and will be in
                                    touch as it moves forward.
                                @elseif ($notificationType === 'confirmed')
                                    Thank you, {{ $order->customer_name }}. We have confirmed your order and are
                                    preparing it with care.
                                @elseif ($notificationType === 'shipped')
                                    Thank you, {{ $order->customer_name }}. Your order has left us and is on its way to
                                    the delivery address below.
                                @elseif ($notificationType === 'delivered')
                                    Thank you, {{ $order->customer_name }}. Your order has been marked as delivered.
                                @elseif ($notificationType === 'cancelled')
                                    {{ $order->customer_name }}, your order has been cancelled. Please contact BENAZIR
                                    if you have questions.
                                @endif
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 36px 8px;">
                            <h2
                                style="margin:0 0 12px; color:#282522; font:normal 19px/1.3 Georgia, 'Times New Roman', serif;">
                                Order details</h2>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="border-collapse:collapse; font:13px/1.5 Arial, sans-serif;">
                                <thead>
                                    <tr style="color:#817a72; text-align:left; border-bottom:1px solid #e8e3dc;">
                                        <th style="padding:9px 4px; font-weight:normal;">Item</th>
                                        <th style="padding:9px 4px; font-weight:normal; text-align:center;">Qty</th>
                                        <th style="padding:9px 4px; font-weight:normal; text-align:right;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr style="border-bottom:1px solid #eeeae5;">
                                            <td style="padding:12px 4px; color:#282522;">
                                                <strong style="font-weight:600;">{{ $item->product_name }}</strong>
                                                <br><span style="color:#817a72;">{{ $item->sku }}@if ($item->size)
                                                        · {{ $item->size }}
                                                        @endif @if ($item->color)
                                                            · {{ $item->color }}
                                                        @endif
                                                </span>
                                            </td>
                                            <td style="padding:12px 4px; text-align:center;">{{ $item->quantity }}</td>
                                            <td style="padding:12px 4px; text-align:right; white-space:nowrap;">
                                                {{ $order->currency }}
                                                {{ number_format((float) $item->line_total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="font:13px/1.7 Arial, sans-serif;">
                                <tr>
                                    <td style="padding:14px 4px 3px; color:#625d57;">Subtotal</td>
                                    <td style="padding:14px 4px 3px; text-align:right;">{{ $order->currency }}
                                        {{ number_format((float) $order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:3px 4px; color:#625d57;">Delivery</td>
                                    <td style="padding:3px 4px; text-align:right;">{{ $order->currency }}
                                        {{ number_format((float) $order->shipping_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 4px; border-top:1px solid #d8d0c7; font-weight:600;">Total
                                    </td>
                                    <td
                                        style="padding:10px 4px; border-top:1px solid #d8d0c7; text-align:right; font-weight:600;">
                                        {{ $order->currency }} {{ number_format((float) $order->grand_total, 2) }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 36px 30px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="border-top:1px solid #e8e3dc; font:13px/1.7 Arial, sans-serif;">
                                <tr>
                                    <td style="padding:16px 4px 3px; color:#817a72;">Payment method</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 4px 12px;">
                                        {{ $paymentMethods[$order->payment_method] ?? str($order->payment_method)->replace('_', ' ')->title() }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 4px 3px; border-top:1px solid #eeeae5; color:#817a72;">
                                        Delivery address</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 4px;">
                                        {{ $order->customer_name }}<br>{{ $order->shipping_address_line_1 }}
                                        @if ($order->shipping_address_line_2)
                                            <br>{{ $order->shipping_address_line_2 }}
                                        @endif
                                        <br>
                                        {{ $order->shipping_city }}@if ($order->shipping_state)
                                            , {{ $order->shipping_state }}
                                            @endif @if ($order->shipping_postal_code)
                                                {{ $order->shipping_postal_code }}
                                            @endif
                                            <br>{{ $order->shipping_country }}<br>{{ $order->customer_phone }}
                                    </td>
                                </tr>
                                @if ($notificationType === 'cancelled' && $order->cancellation_reason)
                                    <tr>
                                        <td style="padding:12px 4px 0; color:#625d57;">Reason:
                                            {{ $order->cancellation_reason }}</td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding:20px 36px; background:#f8f6f3; color:#817a72; text-align:center; font:11px/1.7 Arial, sans-serif;">
                            With care,<br><strong
                                style="color:#7a263a; font-weight:600; letter-spacing:1px;">BENAZIR</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
