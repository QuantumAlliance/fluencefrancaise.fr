<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassType extends Model
{
    use HasFactory;
    
    /**
     * Table name: class_types
     * Purpose: Store different class types (Group, One-on-One, etc.) with pricing
     */
    protected $table = 'class_types';
    
    protected $fillable = [
        'name',              // Alias for class_name (for compatibility)
        'class_name',        // Primary name field (e.g., "Group", "One-on-One")
        'homepage_title',    // Custom title for homepage pricing cards
        'homepage_description', // Short description for homepage cards
        'features',          // JSON list of features
        'is_popular',        // Whether to highlight as popular
        'description',       // Class description
        'price',            // Price per month/period
        'currency',         // Currency code (CAD, USD, etc.)
        'duration',         // Duration type: weekly, monthly, quarterly
        'is_active',         // Active status
        'display_order',      // Order for display in forms
        'is_batch_full',     // Whether the batch is full
        'batch_full_message',  // Message to display when batch is full
        'batch_date',        // Date of the batch (e.g. "Nov 22, 12025")
        'batch_schedule',     // Schedule details (e.g. "Sat & Sun 7AM-9AM PST")
        'disable_coupons'    // Whether coupons are disabled for this class type
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_batch_full' => 'boolean',
        'disable_coupons' => 'boolean',
        'is_popular' => 'boolean',
        'features' => 'array',
        'display_order' => 'integer'
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
