<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('birthday_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nickname')->nullable();
            $table->date('birthday_date')->nullable();
            $table->string('hero_title')->default('Happy Birthday, My Love');
            $table->string('hero_subtitle')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('intro_message')->nullable();
            $table->timestamps();
        });

        Schema::create('memories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('memory_date')->nullable();
            $table->string('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->string('cover_image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('published');
            $table->timestamps();
        });

        Schema::create('memory_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memory_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image');
            $table->string('caption')->nullable();
            $table->date('memory_date')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('birthday_messages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('icon')->default('✦');
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status')->default('published');
            $table->timestamps();
        });

        Schema::create('music_playlists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('youtube_url');
            $table->string('youtube_video_id')->nullable();
            $table->string('youtube_playlist_id')->nullable();
            $table->string('thumbnail')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('love_letters', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('A little something I wanted to tell you...');
            $table->longText('content');
            $table->string('signature')->nullable();
            $table->string('status')->default('published');
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title')->default('Happy Birthday');
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('primary_color')->default('#c9828f');
            $table->string('secondary_color')->default('#d8b47a');
            $table->text('footer_message')->nullable();
            $table->boolean('enable_hearts')->default(true);
            $table->boolean('enable_confetti')->default(true);
            $table->boolean('enable_music')->default(true);
            $table->text('final_message')->nullable();
            $table->string('final_photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('love_letters');
        Schema::dropIfExists('music_playlists');
        Schema::dropIfExists('birthday_messages');
        Schema::dropIfExists('gallery_images');
        Schema::dropIfExists('memory_images');
        Schema::dropIfExists('memories');
        Schema::dropIfExists('birthday_profiles');
    }
};
