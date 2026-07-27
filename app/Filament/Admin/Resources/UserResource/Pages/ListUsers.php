<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Jobs\GenerateMemberQrCodesJob;
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
                ->modalHeading('Generate QR Code untuk semua anggota')
                ->action(static function (): void {
                    GenerateMemberQrCodesJob::dispatch();
                })
                ->successNotificationTitle('Tugas generate QR berhasil dikirim ke antrian'),
        ];
    }
}