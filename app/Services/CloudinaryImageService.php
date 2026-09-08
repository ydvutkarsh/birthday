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
     * Upload an image to Cloudinary.
     *
     * @return array{url: string, public_id: string}
     */
    public function upload(
        UploadedFile $file,
        string $folder,
        ?string $context = null
    ): array {
        try {
            $path = $file->getRealPath() ?: $file->getPathname();

            $response = $this->api()->upload($path, [
                'folder' => $folder,
                'resource_type' => 'image',
                'unique_filename' => true,
                'use_filename' => true,
                'overwrite' => false,
            ]);

            $url = $response['secure_url'] ?? null;
            $publicId = $response['public_id'] ?? null;

            if (
                ! is_string($url) ||
                $url === '' ||
                ! is_string($publicId) ||
                $publicId === ''
            ) {
                throw new CloudinaryImageException(
                    'Cloudinary returned an incomplete image response.'
                );
            }

            return [
                'url' => $url,
                'public_id' => $publicId,
            ];
        } catch (CloudinaryImageException $exception) {
            $this->logUploadFailure(
                $exception,
                $file,
                $folder,
                $context
            );

            throw $exception;
        } catch (Throwable $exception) {
            $this->logUploadFailure(
                $exception,
                $file,
                $folder,
                $context
            );

            throw new CloudinaryImageException(
                'The image could not be uploaded to Cloudinary. Please try again.',
                previous: $exception
            );
        }
    }

    /**
     * Delete an image from Cloudinary.
     */
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
                'exception_message' => $this->safeExceptionMessage($exception),
                'exception_class' => $exception::class,
                'exception_file' => $exception->getFile(),
                'exception_line' => $exception->getLine(),
            ]);

            throw new CloudinaryImageException(
                'The image could not be removed from Cloudinary. The record was kept so it can be retried.',
                previous: $exception
            );
        }
    }

    /**
     * Configure and return Cloudinary Upload API.
     */
    private function api(): UploadApi
    {
        if ($this->uploadApi instanceof UploadApi) {
            return $this->uploadApi;
        }

        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        if (
            ! filled($cloudName) ||
            ! filled($apiKey) ||
            ! filled($apiSecret)
        ) {
            throw new CloudinaryImageException(
                'Cloudinary is not configured. Add CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, and CLOUDINARY_API_SECRET.'
            );
        }

        /*
         * Configure Cloudinary globally.
         */
        Configuration::instance([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
            'url' => [
                'secure' => true,
            ],
        ]);

        $this->uploadApi = new UploadApi();

        return $this->uploadApi;
    }

    /**
     * Log upload errors without exposing credentials.
     */
    private function logUploadFailure(
        Throwable $exception,
        UploadedFile $file,
        string $folder,
        ?string $context
    ): void {
        Log::error('Cloudinary image upload failed.', [
            'exception_message' => $this->safeExceptionMessage($exception),
            'exception_class' => $exception::class,
            'exception_file' => $exception->getFile(),
            'exception_line' => $exception->getLine(),
            'controller' => $context,
            'upload_folder' => $folder,
            'uploaded_filename' => $file->getClientOriginalName(),
            'uploaded_mime_type' => $file->getMimeType(),
            'uploaded_size' => $file->getSize(),
        ]);
    }

    /**
     * Remove sensitive Cloudinary values from exception messages.
     */
    private function safeExceptionMessage(Throwable $exception): string
    {
        $message = $exception->getMessage();

        $secrets = [
            config('services.cloudinary.api_key'),
            config('services.cloudinary.api_secret'),
        ];

        foreach ($secrets as $secret) {
            if (filled($secret)) {
                $message = str_replace(
                    (string) $secret,
                    '[redacted]',
                    $message
                );
            }
        }

        return $message;
    }
}