<?php

namespace Tests;

use App\Models\Admin;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Authenticate the given user on the correct guard.
     *
     * Admins authenticate through the dedicated "admin" guard, while
     * customers use the default "web" guard. Passing no guard for an
     * Admin instance now targets the admin guard automatically, which
     * matches how the auth:admin middleware resolves at runtime.
     */
    public function actingAs(UserContract $user, $guard = null)
    {
        if ($guard === null && $user instanceof Admin) {
            $guard = 'admin';
        }

        return parent::actingAs($user, $guard);
    }
}
