<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Customer account area: profile details and order history.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $orders = $user->orders()->latest()->limit(5)->get();

        return view('storefront.account.show', [
            'customer' => $user,
            'orders' => $orders,
            'page' => 'dashboard',
            'orderCount' => $user->orders()->count(),
        ]);
    }

    public function profile(Request $request): View
    {
        return view('storefront.account.show', [
            'customer' => $request->user(),
            'orders' => collect(),
            'page' => 'profile',
        ]);
    }

    public function orders(Request $request): View
    {
        return view('storefront.account.show', [
            'customer' => $request->user(),
            'orders' => $request->user()->orders()->latest()->paginate(10),
            'page' => 'orders',
        ]);
    }

    /**
     * Update the customer's own profile details.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update($validated);

        return redirect()
            ->route('store.account')
            ->with('success', 'Your profile has been updated.');
    }

    /**
     * Order detail for the signed-in customer (ownership enforced).
     */
    public function order(Request $request, Order $order): View
    {
        $order = $request->user()->orders()
            ->whereKey($order->getKey())
            ->firstOrFail();

        $order->load(['items', 'statusHistories']);

        return view('storefront.checkout.success', [
            'order' => $order,
            'confirmation' => false,
        ]);
    }
}
