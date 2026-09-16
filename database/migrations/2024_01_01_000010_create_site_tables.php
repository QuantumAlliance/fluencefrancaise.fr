<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Read on every page render by resources/views/app.blade.php (lines 17,
        // 23, 38) — a missing settings table 500s the entire site.
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->timestamps();
        });

        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('smtp_host')->nullable();
            $table->string('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('google_client_id')->nullable();
            $table->string('google_client_secret')->nullable();
            $table->string('google_redirect_uri')->nullable();
            $table->text('google_access_token')->nullable();
            $table->text('google_refresh_token')->nullable();
            $table->string('google_from_email')->nullable();
            $table->string('google_from_name')->nullable();
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('status')->default('draft');
            $table->longText('content')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('no_index')->default(false);
            $table->boolean('no_follow')->default(false);
            $table->string('schema_type')->nullable();
            $table->timestamps();

            $table->index(['slug', 'status']);
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('cover_image')->nullable();
            $table->string('link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('meet_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_id')->nullable();
            $table->string('conference_id')->nullable();
            $table->string('meeting_code')->nullable();
            $table->string('organizer_email')->nullable();
            $table->string('actor_email')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->timestamp('event_time')->nullable();
            $table->timestamps();

            $table->index('meeting_code');
            $table->index('actor_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meet_logs');
        Schema::dropIfExists('books');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('email_settings');
        Schema::dropIfExists('settings');
    }
};
