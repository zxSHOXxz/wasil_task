<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait PropertyImageHandler
{
    /**
     * Store new property images
     *
     * @param array $images
     * @return array
     */
    protected function storeImages(array $images): array
    {
        $imagesPaths = [];
        foreach ($images as $image) {
            $path = $image->store('property-images', 'public');
            $imagesPaths[] = $path;
        }
        return $imagesPaths;
    }

    /**
     * Remove a specific image
     *
     * @param string $imagePath
     * @return bool
     */
    protected function removeImage(string $imagePath): bool
    {
        if (Storage::disk('public')->exists($imagePath)) {
            return Storage::disk('public')->delete($imagePath);
        }
        return false;
    }
}
