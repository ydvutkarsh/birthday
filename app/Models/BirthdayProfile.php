<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BirthdayProfile extends Model
{
    protected $fillable = ['name', 'nickname', 'birthday_date', 'hero_title', 'hero_subtitle', 'profile_image', 'cover_image', 'intro_message'];

    protected function casts(): array
    {
        return ['birthday_date' => 'date'];
    }
}
