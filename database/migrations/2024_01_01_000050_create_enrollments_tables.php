<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('class_type_id')->nullable()->constrained('class_types')->nullOnDelete();
            $table->string('status')->default('pending');
            $table->timestamp('enrollment_date')->nullable();
            $table->timestamp('completion_date')->nullable();
            $table->json('form_data')->nullable();
            // Assigned in Enrollment::boot() as max(entry_id) + 1. Indexed but not
            // unique, so historical dumps containing duplicates still import.
            $table->unsignedBigInteger('entry_id')->nullable()->index();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->cascadeOnDelete();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('currency', 8)->default('CAD');
            $table->string('status')->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_payment_method_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->decimal('final_amount', 10, 2)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('transaction_id');
        });

        Schema::create('exam_prep_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_prep_id')->nullable()->constrained('exam_preps')->nullOnDelete();
            $table->foreignId('class_type_id')->nullable()->constrained('class_types')->nullOnDelete();
            $table->string('status')->default('pending');
            $table->timestamp('enrollment_date')->nullable();
            $table->timestamp('completion_date')->nullable();
            $table->json('form_data')->nullable();
            $table->unsignedBigInteger('entry_id')->nullable()->index();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        // Model sets $table = 'exam_prep_progress' (singular).
        Schema::create('exam_prep_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_prep_id')->constrained('exam_preps')->cascadeOnDelete();
            $table->json('state')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'exam_prep_id']);
        });

        // Model sets $table = 'exam_prep_student_access'.
        Schema::create('exam_prep_student_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_prep_id')->constrained('exam_preps')->cascadeOnDelete();
            $table->foreignId('granted_by_tutor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'exam_prep_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_prep_student_access');
        Schema::dropIfExists('exam_prep_progress');
        Schema::dropIfExists('exam_prep_enrollments');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('enrollments');
    }
};
