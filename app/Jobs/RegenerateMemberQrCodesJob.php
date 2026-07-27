<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\MemberQrCodeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RegenerateMemberQrCodesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(MemberQrCodeService $service): void
    {
        User::chunk(100, function ($users) use ($service): void {
            /** @var User $user */
            foreach ($users as $user) {
                if (filled($user->qr_code_path)) {
                    $service->regenerateFor($user);
                }
            }
        });
    }
}
