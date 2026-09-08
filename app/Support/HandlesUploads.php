<?php

namespace App\Support;

use App\Services\CloudinaryImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Throwable;

trait HandlesUploads
{
    /**
     * @return array{url: string, public_id: string}
     */
    protected function upload(?UploadedFile $file, string $directory): ?array
    {
        return $file ? app(CloudinaryImageService::class)->upload($file, $directory) : null;
    }

    protected function removeUpload(?string $path, ?string $publicId = null): void
    {
        if ($publicId) {
            app(CloudinaryImageService::class)->delete($publicId);
        } elseif ($path && ! preg_match('/^https?:\/\//i', $path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /** @param array<int, array{url: string, public_id: string}> $assets */
    protected function cleanupUploadedAssets(array $assets): void
    {
        foreach ($assets as $asset) {
            try {
                app(CloudinaryImageService::class)->delete($asset['public_id']);
            } catch (Throwable) {
                // The original upload error is the useful response; cleanup failures are logged by the service.
            }
        }
    }
}
