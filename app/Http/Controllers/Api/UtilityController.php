<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Course, Coupon};
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    /**
     * Search courses and content - GET /api/utility/search
     */
    public function search(Request $request)
    {
        $query = $request->query('q', '');

        if (strlen($query) < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Search query must be at least 3 characters',
            ], 400);
        }

        $courses = Course::where('title', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'courses' => $courses,
                'total' => $courses->count(),
            ],
            'message' => 'Search results',
        ]);
    }

    /**
     * Validate coupon code - POST /api/utility/validate-coupon
     */
    public function validateCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'class_type_id' => 'nullable|integer',
        ]);

        $code = strtoupper(trim($validated['code']));
        $coupon = Coupon::with('classTypes')->where('code', $code)->first();

        // Check if coupon exists
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code',
            ], 400);
        }

        // Check if coupon is active
        if (isset($coupon->is_active) && $coupon->is_active === false) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code',
            ], 400);
        }

        // Check if coupon has started (start_date validation)
        if ($coupon->start_date && $coupon->start_date > now()->toDateString()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code',
            ], 400);
        }

        // Check if coupon is expired
        if ($coupon->expiry_date && $coupon->expiry_date < now()->toDateString()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code',
            ], 400);
        }

        // Check if coupon is applicable to selected class type
        if (isset($validated['class_type_id']) && $coupon->classTypes->count() > 0) {
            $applicableClassTypeIds = $coupon->classTypes->pluck('id')->toArray();
            if (!in_array($validated['class_type_id'], $applicableClassTypeIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This coupon is not applicable to the selected package',
                ], 400);
            }
        }

        // Check usage limit
        if ($coupon->usage_limit !== null && $coupon->usage_limit > 0) {
            // Count how many times this coupon has been used
            // Note: You may need to add a usage tracking table or field
            // For now, we'll check if usage_limit is set and greater than 0
            // TODO: Implement actual usage tracking if needed
        }

        // Calculate discount
        $discount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discount = ($validated['amount'] * $coupon->discount_value) / 100;
        } else {
            $discount = min($coupon->discount_value, $validated['amount']); // Don't exceed the amount
        }

        $finalAmount = max(0, $validated['amount'] - $discount);

        return response()->json([
            'success' => true,
            'data' => [
                'coupon_code' => $coupon->code,
                'original_amount' => $validated['amount'],
                'discount' => $discount,
                'final_amount' => $finalAmount,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value,
            ],
            'message' => 'Coupon validated successfully',
        ]);
    }

    /**
     * Get system status - GET /api/utility/system-status
     */
    public function systemStatus(Request $request)
    {
        // Get database version
        $dbVersion = 'Unknown';
        try {
            $pdo = \DB::connection()->getPdo();
            $dbVersion = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
            $dbDriver = \DB::connection()->getDriverName();
            $dbVersion = ucfirst($dbDriver) . ' ' . $dbVersion;
        } catch (\Exception $e) {
            $dbVersion = 'Connection Error';
        }

        // Check database connection status
        $dbStatus = 'connected';
        try {
            \DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = 'disconnected';
        }

        // Get PHP version
        $phpVersion = phpversion();

        // Platform version from config or env
        $platformVersion = config('app.version', '2.0.0');
        $buildDate = config('app.build_date', 'Dec 27, 2024');

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $dbStatus === 'connected' ? 'Operational' : 'Degraded',
                'version' => $platformVersion,
                'build_date' => $buildDate,
                'database' => $dbVersion,
                'php_version' => $phpVersion,
                'api_version' => 'v1',
                'timestamp' => now()->toIso8601String(),
            ],
            'message' => 'System status',
        ]);
    }
}
