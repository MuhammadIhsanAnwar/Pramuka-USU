<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class MediaImageService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function resizePublicImage(string $relativePath, int $width, int $height): void
    {
        if (! Storage::disk('public')->exists($relativePath)) {
            return;
        }

        $absolutePath = Storage::disk('public')->path($relativePath);

        $this->manager->decodePath($absolutePath)
            ->cover($width, $height)
            ->save($absolutePath);
    }
}