<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->string('story_eyebrow', 100)->nullable()->after('secondary_color');
            $table->string('story_title', 100)->nullable()->after('story_eyebrow');
            $table->string('story_title_emphasis', 100)->nullable()->after('story_title');
            $table->string('story_description', 500)->nullable()->after('story_title_emphasis');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->dropColumn(['story_eyebrow', 'story_title', 'story_title_emphasis', 'story_description']);
        });
    }
};
