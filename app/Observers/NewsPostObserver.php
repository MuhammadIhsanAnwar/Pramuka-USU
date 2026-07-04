<?php

namespace App\Observers;

use App\Models\NewsPost;
use App\Services\MediaImageService;
use Illuminate\Support\Facades\Storage;

class NewsPostObserver
{
    public function __construct(private readonly MediaImageService $mediaImageService)
    {
    }

    public function saved(NewsPost $newsPost): void
    {
        if ($newsPost->status === 'publish' && blank($newsPost->published_at)) {
            $newsPost->forceFill(['published_at' => now()])->saveQuietly();
        }

        if (blank($newsPost->thumbnail_path) || ! Storage::disk('public')->exists($newsPost->thumbnail_path)) {
            return;
        }

        $this->mediaImageService->resizePublicImage($newsPost->thumbnail_path, 1200, 630);
    }
}