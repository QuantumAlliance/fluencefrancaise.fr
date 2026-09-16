<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Settings;
use Symfony\Component\HttpFoundation\Response;

class StudentPortalMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        $maintenanceMode = Settings::where('key', 'student_portal_maintenance_mode')->first();

        if ($maintenanceMode && $maintenanceMode->value === 'true') {
            // Get the maintenance message
            $maintenanceMessage = Settings::where('key', 'student_portal_maintenance_message')->first();
            $message = $maintenanceMessage ? $maintenanceMessage->value : 'The student portal is currently under maintenance. Please try again later.';

            // For API requests, return JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'maintenance' => true,
                    'message' => $message
                ], 503);
            }

            // For web requests, redirect to maintenance page
            return redirect('/student/maintenance');
        }

        return $next($request);
    }
}
