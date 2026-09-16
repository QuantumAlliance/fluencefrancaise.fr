<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InstallerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // If installation is complete, redirect to home
        if (env('INSTALLER_COMPLETE') === true || env('INSTALLER_COMPLETE') === 'true') {
            return redirect('/');
        }

        return $next($request);
    }
}
