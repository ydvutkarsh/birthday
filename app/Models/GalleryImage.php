<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = ['title', 'image', 'image_public_id', 'caption', 'memory_date', 'sort_order', 'is_featured'];

    protected function casts(): array
    {
        return ['memory_date' => 'date', 'is_featured' => 'boolean'];
    }
}
