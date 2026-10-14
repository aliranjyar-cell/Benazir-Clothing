<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:all,with_orders,repeat'],
        ]);

        $search = $filters['search'] ?? '';
        $type = $filters['type'] ?? 'all';

        $customers = User::query()
            ->withCount('orders')
            ->withSum([
                'orders as lifetime_spend' => fn($query) =>
                $query->where('payment_status', 'paid'),
            ], 'grand_total')
            ->withMax('orders', 'created_at')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when(
                $type === 'with_orders',
                fn($query) =>
                $query->has('orders')
            )
            ->when(
                $type === 'repeat',
                fn($query) =>
                $query->has('orders', '>=', 2)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => User::count(),
            'with_orders' => User::has('orders')->count(),
            'repeat' => User::has('orders', '>=', 2)->count(),
            'paid_revenue' => User::query()
                ->whereHas(
                    'orders',
                    fn($query) =>
                    $query->where('payment_status', 'paid')
                )
                ->withSum([
                    'orders as paid_total' => fn($query) =>
                    $query->where('payment_status', 'paid'),
                ], 'grand_total')
                ->get()
                ->sum('paid_total'),
        ];

        return view('admin.customers.index', compact(
            'customers',
            'stats',
            'search',
            'type'
        ));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }


    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($customer->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $customer->update($validated);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Customer details updated successfully.');
    }


    public function show(User $customer)
    {
        $customer->load([
            'orders' => fn($query) => $query
                ->withCount('items')
                ->latest()
        ]);

        $customer->loadSum([
            'orders as lifetime_spend' => fn($query) =>
            $query->where('payment_status', 'paid'),
        ], 'grand_total');

        $customer->loadCount('orders');

        return view('admin.customers.show', compact('customer'));
    }
}
