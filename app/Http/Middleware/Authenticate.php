<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // For API requests, return null (no redirect)
        if ($request->expectsJson()) {
            return null;
        }

        // For web requests, try to redirect to login route, but return null if route doesn't exist
        try {
            return route('login');
        } catch (\Exception $e) {
            return null;
        }
    }
}
