<?php

namespace App\Services;

use App\Exceptions\CloudinaryImageException;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Throwable;

class CloudinaryImageService
{
    private ?UploadApi $uploadApi = null;

    /**
     * @return array{url: string, public_id: string}
     */
    public function upload(UploadedFile $file, string $folder): array
    {
        try {
            $response = $this->api()->upload($file->getRealPath() ?: $file->getPathname(), [
                'folder' => $folder,
                'resource_type' => 'image',
                'unique_filename' => true,
                'use_filename' => true,
                'overwrite' => false,
            ]);

            $url = $response['secure_url'] ?? null;
            $publicId = $response['public_id'] ?? null;

            if (! is_string($url) || $url === '' || ! is_string($publicId) || $publicId === '') {
                throw new CloudinaryImageException('Cloudinary returned an incomplete image response.');
            }

            return ['url' => $url, 'public_id' => $publicId];
        } catch (CloudinaryImageException $exception) {
            Log::error('Cloudinary image upload failed.', [
                'folder' => $folder,
                'exception' => $exception,
            ]);

            throw $exception;
        } catch (Throwable $exception) {
            Log::error('Cloudinary image upload failed.', [
                'folder' => $folder,
                'exception' => $exception,
            ]);

            throw new CloudinaryImageException(
                'The image could not be uploaded to Cloudinary. Please try again.',
                previous: $exception,
            );
        }
    }

    public function delete(?string $publicId): void
    {
        if (! filled($publicId)) {
            return;
        }

        try {
            $this->api()->destroy($publicId, [
                'resource_type' => 'image',
                'invalidate' => true,
            ]);
        } catch (Throwable $exception) {
            Log::error('Cloudinary image deletion failed.', [
                'public_id' => $publicId,
                'exception' => $exception,
            ]);

            throw new CloudinaryImageException(
                'The image could not be removed from Cloudinary. The record was kept so it can be retried.',
                previous: $exception,
            );
        }
    }

    private function api(): UploadApi
    {
        if ($this->uploadApi) {
            return $this->uploadApi;
        }

        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        if (! filled($cloudName) || ! filled($apiKey) || ! filled($apiSecret)) {
            throw new CloudinaryImageException(
                'Cloudinary is not configured. Add CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, and CLOUDINARY_API_SECRET.'
            );
        }

        $this->uploadApi = new UploadApi(new Configuration([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
        ]));

        return $this->uploadApi;
    }
}
