<?php

namespace App\Support;

class YouTube
{
    public static function parse(string $url): array
    {
        $parts = parse_url(trim($url));
        parse_str($parts['query'] ?? '', $query);
        $host = strtolower($parts['host'] ?? '');
        $videoId = null;
        $playlistId = null;

        if (isset($query['v']) && preg_match('/^[A-Za-z0-9_-]{6,}$/', $query['v'])) {
            $videoId = $query['v'];
        } elseif (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $candidate = trim($parts['path'] ?? '', '/');
            if (preg_match('/^[A-Za-z0-9_-]{6,}$/', $candidate)) {
                $videoId = $candidate;
            }
        }

        if (isset($query['list']) && preg_match('/^[A-Za-z0-9_-]{6,}$/', $query['list'])) {
            $playlistId = $query['list'];
        }

        return [
            'youtube_video_id' => $videoId,
            'youtube_playlist_id' => $playlistId,
            'thumbnail' => $videoId ? "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg" : null,
        ];
    }
}
