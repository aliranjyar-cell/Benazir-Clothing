<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function trackingForm(): View
    {
        return view('storefront.order-tracking', ['order' => null]);
    }

    public function track(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'tracking_token' => ['required', 'uuid'],
        ]);

        $order = Order::query()
            ->where('checkout_token', $validated['tracking_token'])
            ->whereNull('user_id')
            ->with(['items', 'statusHistories'])
            ->first();

        if (! $order || $request->user('web') !== null) {
            return back()->withErrors([
                'tracking_token' => 'We could not verify those tracking details. Check the private code from your order confirmation and try again.',
            ]);
        }

        return view('storefront.order-tracking', compact('order'));
    }

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
