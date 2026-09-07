<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['site_title', 'logo', 'favicon', 'primary_color', 'secondary_color', 'footer_message', 'enable_hearts', 'enable_confetti', 'enable_music', 'final_message', 'final_photo'];

    protected function casts(): array
    {
        return ['enable_hearts' => 'boolean', 'enable_confetti' => 'boolean', 'enable_music' => 'boolean'];
    }
}
