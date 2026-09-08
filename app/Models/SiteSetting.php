<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['site_title', 'logo', 'logo_public_id', 'favicon', 'favicon_public_id', 'primary_color', 'secondary_color', 'story_eyebrow', 'story_title', 'story_title_emphasis', 'story_description', 'footer_message', 'enable_hearts', 'enable_confetti', 'enable_music', 'final_message', 'final_photo', 'final_photo_public_id'];

    protected function casts(): array
    {
        return ['enable_hearts' => 'boolean', 'enable_confetti' => 'boolean', 'enable_music' => 'boolean'];
    }
}
