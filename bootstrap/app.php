<?php
use App\Http\Middleware\EnsureCustomerAuthenticated;
use App\Http\Middleware\EnsureStoreIsOpen;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.customer' => EnsureCustomerAuthenticated::class,
        ]);

        // "guest" routes (/login, /register, /account/login) must send
        // already-signed-in users to the right panel: admins to the admin
        // dashboard, customers to their storefront account area. Without
        // this, the framework default picks the first route named
        // "dashboard" — the admin one — and bounces customers into the
        // admin login flow. (The vendor callback only receives $request,
        // so the active guard is detected from the request's route
        // middleware context / authenticated guards.)
        RedirectIfAuthenticated::redirectUsing(function ($request) {
            return Auth::guard('admin')->check()
                ? route('dashboard')
                : route('store.account');
        });

        // While the store is in maintenance mode, storefront GET pages
        // return a friendly 503 (admin panel routes keep working).
        $middleware->web(append: [
            EnsureStoreIsOpen::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
