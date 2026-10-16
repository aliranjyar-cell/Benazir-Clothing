<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Guest orders use the unguessable checkout token; registered orders
     * remain visible only to their owning customer.
     */
    public function show(Request $request, string $token): View
    {
        $order = Order::query()
            ->where('checkout_token', $token)
            ->firstOrFail();

        $user = $request->user('web');
        $isOwner = $user !== null && $order->user_id === $user->id;

        abort_unless(
            $order->user_id === null ? $user === null : $isOwner,
            404
        );

        $order->load('items');

        return view('storefront.checkout.success', [
            'order' => $order,
            'confirmation' => true,
        ]);
    }
}
