<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Backs both ClassType and Category — Category sets $table = 'class_types'.
        Schema::create('class_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('class_name')->nullable();
            $table->string('homepage_title')->nullable();
            $table->text('homepage_description')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 8)->default('CAD');
            $table->string('duration')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->boolean('is_batch_full')->default(false);
            $table->string('batch_full_message')->nullable();
            $table->string('batch_date')->nullable();
            $table->string('batch_schedule')->nullable();
            $table->boolean('disable_coupons')->default(false);
            $table->timestamps();

            $table->index(['is_active', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_types');
    }
};
