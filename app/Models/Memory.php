<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memory extends Model
{
    protected $fillable = ['title', 'memory_date', 'short_description', 'full_description', 'cover_image', 'cover_image_public_id', 'sort_order', 'is_featured', 'status'];

    protected function casts(): array
    {
        return ['memory_date' => 'date', 'is_featured' => 'boolean'];
    }

    public function images()
    {
        return $this->hasMany(MemoryImage::class)->orderBy('sort_order');
    }
}
