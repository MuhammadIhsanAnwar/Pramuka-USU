<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Jobs\GenerateMemberQrCodesJob;
use App\Jobs\RegenerateMemberQrCodesJob;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Buat Pengguna'),
            Action::make('generateMassQr')
                ->label('Generate Massal QR')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Generate QR Code untuk akun yang belum punya QR')
                ->action(static function (): void {
                    GenerateMemberQrCodesJob::dispatchSync();
                })
                ->successNotificationTitle('QR Code untuk akun tanpa QR berhasil dibuat'),
            Action::make('regenerateMassQr')
                ->label('Regenerate QR Massal')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Regenerate QR Code untuk akun dengan QR lama')
                ->action(static function (): void {
                    RegenerateMemberQrCodesJob::dispatchSync();
                })
                ->successNotificationTitle('QR Code untuk semua akun yang sudah punya QR berhasil digenerate ulang'),
        ];
    }
}