<?php

namespace App\Observers;

use App\Models\Gallery;
use App\Services\MediaImageService;
use Illuminate\Support\Facades\Storage;

class GalleryObserver
{
    public function __construct(private readonly MediaImageService $mediaImageService)
    {
    }

    public function saved(Gallery $gallery): void
    {
        if (blank($gallery->image_path) || ! Storage::disk('public')->exists($gallery->image_path)) {
            return;
        }

        $this->mediaImageService->resizePublicImage($gallery->image_path, 1200, 900);
    }
}