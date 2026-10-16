<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    /**
     * Show the session cart priced from the database.
     */
    public function index(): View
    {
        return view('storefront.cart', [
            'lines' => $this->cart->lines(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    /**
     * Add a variant to the cart. Product availability, identity and
     * stock are verified server-side; prices are never accepted from
     * the browser.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:999'],
        ], [], [
            'variant_id' => 'product variant',
        ]);

        $this->cart->add(
            (int) $validated['variant_id'],
            (int) ($validated['quantity'] ?? 1)
        );

        return redirect()
            ->route('store.cart.index')
            ->with('success', 'Added to your shopping bag.');
    }

    /**
     * Update a line quantity (zero removes the line).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'update' => ['required', 'array', 'min:1'],
            'update.*' => ['required', 'integer', 'min:0', 'max:999'],
        ]);

        foreach ($validated['update'] as $variantId => $quantity) {
            $this->cart->update((int) $variantId, (int) $quantity);
        }

        return redirect()
            ->route('store.cart.index')
            ->with('success', 'Shopping bag updated.');
    }

    /**
     * Remove a line from the cart.
     */
    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'variant_id' => ['required', 'integer', 'min:1'],
        ]);

        $this->cart->remove((int) $validated['variant_id']);

        return redirect()
            ->route('store.cart.index')
            ->with('success', 'Item removed from your shopping bag.');
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return redirect()
            ->route('store.cart.index')
            ->with('success', 'Your shopping bag is empty.');
    }
}
