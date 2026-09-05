<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('wordpress_password')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('title')->nullable();
            $table->text('biography')->nullable();
            // Values seen in AdminController: student, tutor, admin, super_admin.
            $table->string('user_type')->default('student');
            $table->json('permissions')->nullable();
            $table->string('timezone')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('location')->nullable();
            $table->boolean('payment_confirmed')->default(false);
            $table->string('working_status')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('user_type');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
