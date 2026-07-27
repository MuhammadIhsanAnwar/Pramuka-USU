<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Services\QrCodeService;

class MemberQrCodeService
{
    public const STORAGE_DIRECTORY = 'qr_profil';

    public function generateFor(User $member, bool $force = false): string
    {
        if (blank($member->uuid)) {
            $member->uuid = (string) Str::uuid();
            $member->saveQuietly();
        }

        $relativePath = $this->resolveRelativePath($member);
        $absolutePath = $this->resolveAbsolutePath($relativePath);

        if ($force && filled($member->qr_code_path) && $member->qr_code_path !== $relativePath) {
            $oldPath = $this->resolveAbsolutePath($member->qr_code_path);

            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        if (! $force && filled($member->qr_code_path) && File::exists($absolutePath)) {
            return $member->qr_code_path;
        }

        $appUrl = rtrim(config('app.url', env('APP_URL', 'http://localhost')), '/');
        $qrUrl = sprintf('%s/member/%s', $appUrl, $member->uuid);

        app(QrCodeService::class)->generateSvg($qrUrl, $relativePath);

        $member->forceFill(['qr_code_path' => $relativePath])->saveQuietly();

        return $relativePath;
    }

    private function resolveAbsolutePath(string $relativePath): string
    {
        return public_path('storage/' . $relativePath);
    }

    public function regenerateFor(User $member): string
    {
        return $this->generateFor($member, true);
    }

    private function resolveRelativePath(User $member): string
    {
        $filename = Str::slug($member->name ?? 'anggota');
        $filename = Str::substr($filename, 0, 50);

        return sprintf('%s/%s-%s.svg', self::STORAGE_DIRECTORY, $filename, $member->uuid);
    }
}
