<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'enrollment_id',
        'amount',
        'currency',
        'status',
        'transaction_id',
        'stripe_customer_id',
        'stripe_payment_method_id',
        'coupon_code',
        'discount_amount',
        'final_amount',
        'metadata',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'metadata' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user() { 
        return $this->belongsTo(User::class); 
    }
    
    public function enrollment() { 
        return $this->belongsTo(Enrollment::class); 
    }
}
