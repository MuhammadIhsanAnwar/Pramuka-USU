<?php

namespace App\Filament\Admin\Resources\EventAgendaResource\Pages;

use App\Filament\Admin\Resources\EventAgendaResource;
use App\Models\User;
use App\Services\QrCodeService;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class ViewEventAgenda extends ViewRecord
{
    protected static string $resource = EventAgendaResource::class;
    protected static ?string $title = 'Detail Agenda';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('presensiSettings')
                ->label('Atur Presensi')
                ->form([
                    TextInput::make('latitude')
                        ->label('Latitude Presensi')
                        ->numeric()
                        ->required()
                        ->default(fn (): ?float => $this->getRecord()->latitude),
                    TextInput::make('longitude')
                        ->label('Longitude Presensi')
                        ->numeric()
                        ->required()
                        ->default(fn (): ?float => $this->getRecord()->longitude),
                    TextInput::make('radius')
                        ->label('Radius Presensi (meter)')
                        ->numeric()
                        ->required()
                        ->default(fn (): int => $this->getRecord()->radius ?? 500),
                ])
                ->modalHeading('Atur Pengaturan Presensi')
                ->modalWidth('lg')
                ->action(function (array $data): void {
                    $eventAgenda = $this->getRecord();
                    $eventAgenda->forceFill($data);

                    if (blank($eventAgenda->qr_token)) {
                        $eventAgenda->qr_token = (string) Str::uuid();
                    }

                    $relative = URL::temporarySignedRoute(
                        'attendance.scan',
                        now()->addHours(6),
                        [
                            'eventAgenda' => $eventAgenda->slug,
                            'token' => $eventAgenda->qr_token,
                        ],
                        false,
                    );

                    $signedUrl = rtrim(config('app.url'), '/') . $relative;

                    $qrPath = 'agendas/qr/'.$eventAgenda->slug.'.svg';
                    $qrCodeService = app(QrCodeService::class);
                    $eventAgenda->qr_code_path = $qrCodeService->generateSvg($signedUrl, $qrPath);
                    $eventAgenda->save();

                    $this->notify('success', 'Pengaturan presensi berhasil disimpan dan QR Presensi dihasilkan.');
                }),
            Action::make('scanUser')
                ->label('Scan QR User')
                ->url(fn (): string => rtrim(config('app.url'), '/') . route('admin.attendance.scan-user', $this->getRecord(), false))
                ->openUrlInNewTab(),
            Action::make('refreshQr')
                ->label('Segarkan Token Presensi')
                ->requiresConfirmation()
                ->action('refreshQr')
                ->color('warning'),
            Action::make('viewQr')
                ->label('Munculkan QR Presensi')
                ->modalHeading('QR Presensi')
                ->modalContent(function () {
                    $eventAgenda = $this->getRecord()->fresh();

                    if (blank($eventAgenda->qr_token)) {
                        $this->ensurePresensiQrIsGenerated($eventAgenda);
                        $eventAgenda = $this->getRecord()->fresh();
                    }

                    return view('filament.admin.event-agenda-qr', [
                        'eventAgenda' => $eventAgenda,
                        'signedUrl' => $this->getAttendanceScanSignedUrl($eventAgenda),
                    ]);
                }),
        ];
    }

    public function form(Schema $schema): Schema
    {
        $eventAgenda = $this->getRecord();
        $allUsers = User::query()
            ->where('is_active', true)
            ->whereDoesntHave('roles', fn ($query) => $query->where('name', 'Admin'))
            ->with(['attendances' => fn ($query) => $query->where('event_agenda_id', $eventAgenda->id)])
            ->orderBy('name')
            ->get();

        return $schema->columns(1)->components([
            Html::make(fn (): string => view('filament.admin.event-agenda-view', [
                'eventAgenda' => $eventAgenda,
                'users' => $allUsers,
            ])->render()),
        ]);
    }

    public function refreshQr(): void
    {
        $eventAgenda = $this->getRecord();
        $eventAgenda->forceFill([
            'qr_token' => (string) Str::uuid(),
        ]);

        $this->ensurePresensiQrIsGenerated($eventAgenda, true);

        $this->notify('success', 'Token presensi berhasil diperbarui dan QR disegarkan.');
    }

    private function ensurePresensiQrIsGenerated($eventAgenda, bool $force = false): void
    {
        if (blank($eventAgenda->qr_token)) {
            $eventAgenda->qr_token = (string) Str::uuid();
        }

        $qrFileMissing = blank($eventAgenda->qr_code_path)
            || ! File::exists(public_path('storage/'.$eventAgenda->qr_code_path));

        if ($force || $qrFileMissing) {
            $relativePath = sprintf(
                'qr_presensi/%s_%s.svg',
                now()->format('Ymd_His'),
                Str::slug($eventAgenda->name),
            );

            $eventAgenda->qr_code_path = app(QrCodeService::class)->generateSvg(
                $this->getAttendanceScanSignedUrl($eventAgenda),
                $relativePath,
            );
        }

        $eventAgenda->saveQuietly();
    }

    private function getAttendanceScanSignedUrl($eventAgenda): string
    {
        return rtrim(config('app.url'), '/') . URL::temporarySignedRoute(
            'attendance.scan',
            now()->addHours(6),
            [
                'eventAgenda' => $eventAgenda->slug,
                'token' => $eventAgenda->qr_token,
            ],
            false,
        );
    }
}
