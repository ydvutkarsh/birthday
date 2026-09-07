<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesUploads
{
    protected function upload(?UploadedFile $file, string $directory): ?string
    {
        return $file?->store($directory, 'public');
    }

    protected function removeUpload(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
