<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreIsOpen
{
    /**
     * While the store is in maintenance mode, storefront GET pages return
     * a friendly 503 page. Signed-in admins and the admin/auth routes keep
     * working so the panel can be used to reopen the store.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->isMethod('GET')
            && Setting::get('store_status', 'open') === 'maintenance'
            && !$this->isAllowed($request)
        ) {
            return response()->view('storefront.maintenance', [
                'storeSettings' => Setting::allSettings(),
            ], 503);
        }

        return $next($request);
    }

    private function isAllowed(Request $request): bool
    {
        if (auth('admin')->check()) {
            return true;
        }

        return $request->routeIs(
            'dashboard',
            'login',
            'logout',
            'password.*',
            'profile.*',
            'products.*',
            'categories.*',
            'admins.*',
            'admin.*',
            'settings.*',
            'store.login',
            'store.logout',
        );
    }
}