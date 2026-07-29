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

                    $signedUrl = URL::temporarySignedRoute(
                        'attendance.scan',
                        now()->addHours(6),
                        [
                            'eventAgenda' => $eventAgenda->slug,
                            'token' => $eventAgenda->qr_token,
                        ],
                    );

                    $qrPath = 'agendas/qr/'.$eventAgenda->slug.'.svg';
                    $qrCodeService = app(QrCodeService::class);
                    $eventAgenda->qr_code_path = $qrCodeService->generateSvg($signedUrl, $qrPath);
                    $eventAgenda->save();

                    $this->notify('success', 'Pengaturan presensi berhasil disimpan dan QR Presensi dihasilkan.');
                }),
            Action::make('scanUser')
                ->label('Scan QR User')
                ->url(fn (): string => route('admin.attendance.scan-user', $this->getRecord()))
                ->openUrlInNewTab(),
            Action::make('refreshQr')
                ->label('Segarkan Token Presensi')
                ->requiresConfirmation()
                ->action('refreshQr')
                ->color('warning'),
            Action::make('viewQr')
                ->label('Munculkan QR Presensi')
                ->modalHeading('QR Presensi')
                ->modalContent(fn (): string => view('filament.admin.event-agenda-qr', [
                    'eventAgenda' => $this->getRecord(),
                    'signedUrl' => URL::temporarySignedRoute(
                        'attendance.scan',
                        now()->addHours(6),
                        [
                            'eventAgenda' => $this->getRecord()->slug,
                            'token' => $this->getRecord()->qr_token,
                        ],
                    ),
                ])->render()),
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
        $qrToken = Str::uuid();

        $eventAgenda->forceFill([
            'qr_token' => $qrToken,
        ])->saveQuietly();

        $signedUrl = URL::temporarySignedRoute(
            'attendance.scan',
            now()->addHours(6),
            [
                'eventAgenda' => $eventAgenda->slug,
                'token' => $qrToken,
            ],
        );

        $qrPath = 'agendas/qr/'.$eventAgenda->slug.'.svg';
        $qrCodeService = app(QrCodeService::class);

        $eventAgenda->forceFill([
            'qr_code_path' => $qrCodeService->generateSvg($signedUrl, $qrPath),
        ])->saveQuietly();

        $this->notify('success', 'Token presensi berhasil diperbarui dan QR disegarkan.');
    }
}
