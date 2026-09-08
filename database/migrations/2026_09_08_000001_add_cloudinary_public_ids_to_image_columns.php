<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('birthday_profiles', function (Blueprint $table) {
            $table->string('profile_image_public_id')->nullable();
            $table->string('cover_image_public_id')->nullable();
        });

        Schema::table('memories', function (Blueprint $table) {
            $table->string('cover_image_public_id')->nullable();
        });

        Schema::table('memory_images', function (Blueprint $table) {
            $table->string('image_public_id')->nullable();
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->string('image_public_id')->nullable();
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('logo_public_id')->nullable();
            $table->string('favicon_public_id')->nullable();
            $table->string('final_photo_public_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['logo_public_id', 'favicon_public_id', 'final_photo_public_id']);
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropColumn('image_public_id');
        });

        Schema::table('memory_images', function (Blueprint $table) {
            $table->dropColumn('image_public_id');
        });

        Schema::table('memories', function (Blueprint $table) {
            $table->dropColumn('cover_image_public_id');
        });

        Schema::table('birthday_profiles', function (Blueprint $table) {
            $table->dropColumn(['profile_image_public_id', 'cover_image_public_id']);
        });
    }
};
