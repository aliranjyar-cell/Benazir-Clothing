<?php
namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Order confirmation. Access is limited to the owning customer or
     * to the browser session that just placed the order (guests).
     */
    public function show(Request $request, Order $order): View
    {
        $sessionOrderIds = array_map(
            'intval',
            (array) $request->session()->get('storefront.recent_orders', [])
        );

        $isOwner = $request->user() !== null
            && $order->user_id === $request->user()->id;

        abort_unless($isOwner || in_array($order->id, $sessionOrderIds, true), 404);

        $order->load('items');

        return view('storefront.checkout.success', [
            'order' => $order,
            'confirmation' => true,
        ]);
    }
}
