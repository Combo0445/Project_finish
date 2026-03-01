<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    /**
     * Handle the upload of an array of images.
     *
     * @param array|null $files Array of uploaded files.
     * @param string $directory Storage directory (e.g., 'pictures').
     * @return string JSON encoded array of paths.
     */
    public function handleMultipleUploads(?array $files, string $directory): string
    {
        $paths = [];
        if ($files) {
            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $paths[] = $file->store($directory, 'public');
                }
            }
        }
        return json_encode($paths);
    }

    /**
     * Delete existing images from JSON array.
     *
     * @param string|null $jsonPaths JSON encoded paths.
     */
    public function deleteMultipleImages(?string $jsonPaths): void
    {
        if ($jsonPaths) {
            $paths = json_decode($jsonPaths, true);
            if (is_array($paths)) {
                foreach ($paths as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
        }
    }

    /**
     * Handle the upload of a single image.
     *
     * @param UploadedFile|null $file The uploaded file.
     * @param string $directory Storage directory.
     * @param string|null $oldPath The old file path to delete.
     * @return string|null The new file path.
     */
    public function handleSingleUpload(?UploadedFile $file, string $directory, ?string $oldPath = null): ?string
    {
        if ($file) {
            $this->deleteSingleImage($oldPath);
            return $file->store($directory, 'public');
        }
        return $oldPath;
    }

    /**
     * Delete an existing single image.
     *
     * @param string|null $path The file path.
     */
    public function deleteSingleImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
