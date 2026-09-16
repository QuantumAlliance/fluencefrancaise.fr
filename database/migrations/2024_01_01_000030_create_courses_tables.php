<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_title');
            $table->string('course_subtitle')->nullable();
            $table->longText('course_description')->nullable();
            // Both columns are genuinely used: CourseController validates
            // 'category_id' => exists:class_types,id, while views read
            // 'course_category' as a free-text label.
            $table->string('course_category')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('class_types')->nullOnDelete();
            $table->string('course_language')->nullable();
            $table->string('course_level')->nullable();
            $table->string('course_level_custom')->nullable();
            $table->integer('course_total_texts')->default(0);
            $table->longText('course_json_content')->nullable();
            $table->string('course_image')->nullable();
            $table->string('course_banner')->nullable();
            $table->boolean('course_is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->string('custom_url')->nullable();
            $table->string('custom_url_target')->nullable();
            $table->timestamps();

            $table->index(['course_is_active', 'display_order']);
        });

        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('course_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_lesson_id')->constrained('course_lessons')->cascadeOnDelete();
            $table->string('type')->nullable();
            $table->longText('content_json')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_activities');
        Schema::dropIfExists('course_lessons');
        Schema::dropIfExists('course_sections');
        Schema::dropIfExists('courses');
    }
};
