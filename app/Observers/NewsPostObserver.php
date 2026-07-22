<?php

namespace App\Observers;

use App\Models\NewsPost;
use App\Services\MediaImageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsPostObserver
{
    public function __construct(private readonly MediaImageService $mediaImageService)
    {
    }

    public function retrieved(NewsPost $newsPost): void
    {
        if ($newsPost->status === 'draft' && $newsPost->published_at?->isPast()) {
            $newsPost->forceFill(['status' => 'publish'])->saveQuietly();
        }
    }

    public function saved(NewsPost $newsPost): void
    {
        if ($newsPost->status === 'publish') {
            if (blank($newsPost->published_at)) {
                $newsPost->forceFill(['published_at' => now('Asia/Jakarta')])->saveQuietly();
            } elseif ($newsPost->published_at->isFuture()) {
                $newsPost->forceFill(['status' => 'draft'])->saveQuietly();
            }
        }

        if ($newsPost->status === 'draft' && $newsPost->published_at?->isPast()) {
            $newsPost->forceFill(['status' => 'publish'])->saveQuietly();
        }

        $this->moveAndOptimizeImages($newsPost);
    }

    private function moveAndOptimizeImages(NewsPost $newsPost): void
    {
        $slugFolder = trim($newsPost->slug, '/');

        if (blank($slugFolder)) {
            return;
        }

        $targetDirectory = "berita/{$slugFolder}";
        Storage::disk('public')->makeDirectory($targetDirectory);

        $updated = false;
        $thumbnailPath = $newsPost->thumbnail_path;

        if (filled($thumbnailPath) && Storage::disk('public')->exists($thumbnailPath)) {
            $newThumbnailPath = $this->moveToSlugFolder($thumbnailPath, $targetDirectory);

            if ($newThumbnailPath !== $thumbnailPath) {
                $thumbnailPath = $newThumbnailPath;
                $updated = true;
            }

            $this->mediaImageService->resizePublicImage($thumbnailPath, 1200, 1200, 80);
        }

        $imagePaths = [];

        foreach ($newsPost->image_paths ?? [] as $imagePath) {
            if (blank($imagePath) || ! Storage::disk('public')->exists($imagePath)) {
                continue;
            }

            $newImagePath = $this->moveToSlugFolder($imagePath, $targetDirectory);
            $imagePaths[] = $newImagePath;
            $this->mediaImageService->resizePublicImage($newImagePath, 1200, 1200, 80);

            if ($newImagePath !== $imagePath) {
                $updated = true;
            }
        }

        if ($updated) {
            $newsPost->forceFill([
                'thumbnail_path' => $thumbnailPath,
                'image_paths' => $imagePaths,
            ])->saveQuietly();
        }
    }

    private function moveToSlugFolder(string $path, string $targetDirectory): string
    {
        if (str_starts_with($path, "{$targetDirectory}/")) {
            return $path;
        }

        $filename = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $newFilename = Str::slug($filename) . '-' . uniqid();
        $newPath = trim("{$targetDirectory}/{$newFilename}.{$extension}", '/');

        if (Storage::disk('public')->exists($newPath)) {
            $newPath = trim("{$targetDirectory}/{$newFilename}-" . uniqid() . ".{$extension}", '/');
        }

        Storage::disk('public')->move($path, $newPath);

        return $newPath;
    }
}