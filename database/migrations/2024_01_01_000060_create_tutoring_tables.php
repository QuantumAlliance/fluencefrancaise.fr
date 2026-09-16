<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('tutor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Group::students() — belongsToMany(User, 'group_student', 'group_id',
        // 'student_id')->withTimestamps().
        Schema::create('group_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['group_id', 'student_id']);
        });

        // Model sets $table = 'homework' (uncountable).
        Schema::create('homework', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->string('submission_path')->nullable();
            $table->string('submission_name')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->string('status')->default('assigned');
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
        });

        // Eloquent resolves StudentProgress to 'student_progress' — "Progress" is
        // uncountable, so this is NOT student_progresses.
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->cascadeOnDelete();
            $table->string('activity_type')->nullable();
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->json('section_data')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'course_id']);
        });

        // No model — reached only via DB::table('student_records'), guarded by
        // Schema::hasTable(). One row per student; 'records' and 'syllabus' hold
        // JSON written with json_encode(), so they are plain longText.
        Schema::create('student_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->longText('records')->nullable();
            $table->longText('syllabus')->nullable();
            $table->timestamps();
        });

        Schema::create('tutor_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('note_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('tutor_student_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->timestamps();

            $table->unique(['tutor_id', 'student_id', 'course_id'], 'tutor_student_course_unique');
        });

        Schema::create('tutor_vacations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users')->cascadeOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('reason')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        // record_id points at an entry inside student_records.records JSON, not a
        // table row, so it is deliberately left unconstrained.
        Schema::create('timer_edit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tutor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('record_id')->nullable();
            $table->date('record_date')->nullable();
            $table->string('old_timer')->nullable();
            $table->string('new_timer')->nullable();
            $table->string('status')->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action')->nullable();
            $table->string('model')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['model', 'model_id']);
        });

        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('preference_key');
            $table->longText('preference_value')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'preference_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('timer_edit_requests');
        Schema::dropIfExists('tutor_vacations');
        Schema::dropIfExists('tutor_student_assignments');
        Schema::dropIfExists('tutor_notes');
        Schema::dropIfExists('student_records');
        Schema::dropIfExists('student_progress');
        Schema::dropIfExists('homework');
        Schema::dropIfExists('group_student');
        Schema::dropIfExists('groups');
    }
};
