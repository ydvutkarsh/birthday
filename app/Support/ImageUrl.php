<?php

namespace App\Support;

class ImageUrl
{
    public static function for(?string $path): ?string
    {
        if (! filled($path)) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
