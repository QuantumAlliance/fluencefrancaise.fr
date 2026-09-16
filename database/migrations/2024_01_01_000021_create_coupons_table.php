<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->unique();
            $table->string('discount_type')->default('percentage');
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->string('duration')->default('once');
            $table->integer('duration_in_months')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Coupon::classTypes() — belongsToMany(ClassType, 'coupon_class_type').
        Schema::create('coupon_class_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
            $table->foreignId('class_type_id')->constrained('class_types')->cascadeOnDelete();

            $table->unique(['coupon_id', 'class_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_class_type');
        Schema::dropIfExists('coupons');
    }
};
