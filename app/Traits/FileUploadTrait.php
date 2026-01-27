<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{
    /**
     * Upload a single file to storage
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string URL of the uploaded file
     */
    protected function uploadSingleFile(UploadedFile $file, string $directory): string
    {
        $path = $file->store($directory, 'public');
        return asset('storage/' . $path);
    }

    /**
     * Upload multiple files to storage
     *
     * @param array $files
     * @param string $directory
     * @return array URLs of the uploaded files
     */
    protected function uploadMultipleFiles(array $files, string $directory): array
    {
        $urls = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $urls[] = $this->uploadSingleFile($file, $directory);
            }
        }
        return $urls;
    }

    /**
     * Delete a file from storage by URL
     *
     * @param string|null $url
     * @return bool
     */
    protected function deleteFile(?string $url): bool
    {
        if (!$url) {
            return false;
        }

        // Extract path from URL (remove domain and /storage/ prefix)
        $path = str_replace(asset('storage/'), '', $url);

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Delete multiple files from storage by URLs
     *
     * @param array|null $urls
     * @return void
     */
    protected function deleteMultipleFiles(?array $urls): void
    {
        if (!$urls || !is_array($urls)) {
            return;
        }

        foreach ($urls as $url) {
            if (is_string($url)) {
                $this->deleteFile($url);
            }
        }
    }
}
