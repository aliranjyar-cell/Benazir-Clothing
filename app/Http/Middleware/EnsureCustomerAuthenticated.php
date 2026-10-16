<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;

class EnsureCustomerAuthenticated extends Authenticate
{
    /**
     * Send unauthenticated customers to the customer login page
     * instead of the admin login page used by the default "auth" middleware.
     */
    protected function redirectTo($request)
    {
        return route('store.login');
    }
}
