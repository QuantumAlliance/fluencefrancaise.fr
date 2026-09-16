<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    
    /**
     * Table name: coupons
     * Purpose: Store discount coupons with codes, types, and limits
     */
    protected $table = 'coupons';
    
    protected $fillable = [
        'name',            // Coupon display name
        'code',            // Unique coupon code (e.g., "SAVE20")
        'discount_type',   // 'percentage' or 'fixed'
        'discount_value',  // Discount amount (percentage or fixed amount)
        'start_date',      // When coupon becomes valid
        'expiry_date',     // When coupon expires
        'usage_limit',     // Maximum uses (null = unlimited)
        'duration',        // 'once' (first month), 'forever', or 'repeating'
        'duration_in_months', // Number of months for 'repeating' duration
        'is_active'        // Active status
    ];
    
    protected $casts = [
        'discount_value' => 'decimal:2',
        'start_date' => 'date',
        'expiry_date' => 'date',
        'usage_limit' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Class types this coupon is applicable to
     * If empty, coupon applies to all class types
     */
    public function classTypes()
    {
        return $this->belongsToMany(ClassType::class, 'coupon_class_type');
    }
}
