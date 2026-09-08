<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemoryImage extends Model
{
    protected $fillable = ['memory_id', 'image', 'image_public_id', 'caption', 'sort_order'];

    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }
}
