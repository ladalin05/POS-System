<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MinioStorageService
{
    public function upload(UploadedFile $file, ?string $folder = 'image'): array
    {
        $maxBytes = (int) env('UPLOAD_MAX_MB', 10) * 1024 * 1024;
        if ($file->getSize() > $maxBytes) {
            throw ValidationException::withMessages([
                'file' => ['The file may not be greater than ' . env('UPLOAD_MAX_MB', 10) . ' MB.'],
            ]);
        }

        $safeFolder = trim($folder ?: 'image', '/');
        $extension  = strtolower($file->getClientOriginalExtension() ?: 'bin');
        $path       = $safeFolder . '/' . Str::uuid() . '.' . $extension;

        Storage::disk('minio')->put($path, file_get_contents($file->getPathname()));

        $url = rtrim(env('MINIO_ENDPOINT'), '/') . '/' . env('MINIO_BUCKET') . '/' . $path;

        return [
            'path'         => $path,
            'bucket'       => env('MINIO_BUCKET'),
            'public_url'   => $url,
            'download_url' => $url,
            'token'        => null,
            'content_type' => $file->getMimeType() ?: 'application/octet-stream',
        ];
    }

    public function delete(string $path): void
    {
        Storage::disk('minio')->delete($path);
    }
}
