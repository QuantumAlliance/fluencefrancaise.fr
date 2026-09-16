<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_preps', function (Blueprint $table) {
            $table->id();
            $table->string('exam_prep_title');
            $table->string('exam_prep_subtitle')->nullable();
            $table->longText('exam_prep_description')->nullable();
            $table->string('exam_prep_description_title')->nullable();
            $table->string('exam_prep_category')->nullable();
            $table->string('exam_prep_oral_layout')->nullable();
            $table->string('exam_prep_language')->nullable();
            $table->string('exam_prep_level')->nullable();
            $table->string('exam_prep_level_custom')->nullable();
            $table->integer('exam_prep_total_texts')->default(0);
            $table->longText('exam_prep_json_content')->nullable();
            $table->string('exam_prep_image')->nullable();
            $table->string('exam_prep_banner')->nullable();
            $table->boolean('exam_prep_is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->string('custom_url')->nullable();
            $table->string('custom_url_target')->nullable();
            $table->timestamps();

            $table->index(['exam_prep_is_active', 'display_order']);
        });

        Schema::create('exam_prep_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_prep_id')->constrained('exam_preps')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('exam_prep_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_prep_section_id')->constrained('exam_prep_sections')->cascadeOnDelete();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('exam_prep_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_prep_lesson_id')->constrained('exam_prep_lessons')->cascadeOnDelete();
            $table->string('type')->nullable();
            $table->longText('content_json')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_prep_activities');
        Schema::dropIfExists('exam_prep_lessons');
        Schema::dropIfExists('exam_prep_sections');
        Schema::dropIfExists('exam_preps');
    }
};
