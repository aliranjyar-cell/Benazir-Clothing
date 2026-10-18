<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    /**
     * Customer login form. The existing "/login" route remains the
     * admin login; this is the storefront sign-in for customers.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('store.account');
        }

        return view('storefront.account.login');
    }

    /**
     * Authenticate a customer against the default (web/users) guard.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [], [
            'email' => 'email address',
        ]);

        $credentials['email'] = Str::lower($credentials['email']);
        $throttleKey = Str::transliterate($credentials['email'] . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        if (! Auth::guard('web')->attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return $this->customerRedirect($request);
    }

    /**
     * Send a signed-in customer to their account area.
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

    /**
     * Sign the customer out.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return redirect()->route('store.home');
    }
}
