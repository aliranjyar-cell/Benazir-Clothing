<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\- ]{7,30}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
        ]);

        event(new Registered($user));

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return $this->customerRedirect($request);
    }

    /**
     * Send a freshly registered customer to their account area.
     *
     * The session's "url.intended" key is shared with the admin panel's
     * auth guard, so a stale admin URL (e.g. /dashboard, stored when an
     * unauthenticated visitor was bounced from the admin area) must not
     * hijack this redirect — only storefront /account URLs are honored.
     */
    private function customerRedirect(Request $request): RedirectResponse
    {
        $intended = $request->session()->get('url.intended');
        $path = $intended ? parse_url($intended, PHP_URL_PATH) : null;

        if ($path === null || ! str_starts_with($path, '/account')) {
            $request->session()->forget('url.intended');

            return redirect()->route('store.account');
        }

        return redirect()->intended(route('store.account', absolute: false));
    }
}
