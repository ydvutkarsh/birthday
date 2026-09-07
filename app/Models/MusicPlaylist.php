<?php

namespace App\Models;

use App\Support\YouTube;
use Illuminate\Database\Eloquent\Model;

class MusicPlaylist extends Model
{
    protected $fillable = ['title', 'youtube_url', 'youtube_video_id', 'youtube_playlist_id', 'thumbnail', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public static function prepareUrl(string $url): array
    {
        return YouTube::parse($url);
    }
}
