<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemoryImage extends Model
{
    protected $fillable = ['memory_id', 'image', 'caption', 'sort_order'];

    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }
}
