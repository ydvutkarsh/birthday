<?php

use App\Support\ImageUrl;

if (! function_exists('image_url')) {
    function image_url(?string $path): ?string
    {
        return ImageUrl::for($path);
    }
}
