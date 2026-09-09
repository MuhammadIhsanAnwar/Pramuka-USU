<?php

namespace App\Filament\Admin\Resources\AttendanceResource\Pages;

use App\Filament\Admin\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\EventAgenda;
use App\Models\User;
use App\Services\QrCodeService;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewAttendance extends ViewRecord
{
    public ?EventAgenda $qrEventAgenda = null;

    protected static string $resource = AttendanceResource::class;
    protected static ?string $title = 'Detail Presensi';

    protected function getHeaderActions(): array
    {
        $eventAgenda = $this->record->agenda;

        if (! $eventAgenda) {
            return [];
        }

        return [
            Action::make('scanUser')
                ->label('Scan QR User')
                ->url(fn (): string => rtrim(config('app.url'), '/') . route('admin.attendance.scan-user', $eventAgenda, false))
                ->openUrlInNewTab(),
            Action::make('viewQr')
                ->label('Munculkan QR Presensi')
                ->modalHeading('QR Presensi')
                ->modalContent(function () use ($eventAgenda) {
                    $eventAgenda = $this->qrEventAgenda ?: $eventAgenda->fresh();

                    if (blank($eventAgenda->qr_token)) {
                        $this->ensurePresensiQrIsGenerated($eventAgenda);
                        $eventAgenda = $this->qrEventAgenda ?: $eventAgenda->fresh();
                    }

                    $signedUrl = rtrim(config('app.url'), '/') . URL::temporarySignedRoute(
                        'attendance.scan',
                        now()->addHours(6),
                        [
                            'eventAgenda' => $eventAgenda->slug,
                            'token' => $eventAgenda->qr_token,
                        ],
                        false,
                    );

                    return view('filament.admin.event-agenda-qr', [
                        'eventAgenda' => $eventAgenda,
                        'signedUrl' => $signedUrl,
                    ]);
                }),
        ];
    }

    public function form(Schema $schema): Schema
    {
        $eventAgenda = $this->record->agenda;
        $users = collect();

        if ($eventAgenda) {
            $allowedSorts = [
                'name' => 'name',
                'jenis_user' => 'jenis_user',
                'satuan' => 'satuan',
            ];

            $sort = request()->query('sort', 'name');
            $direction = request()->query('direction', 'asc') === 'desc' ? 'desc' : 'asc';
            $sortColumn = $allowedSorts[$sort] ?? 'name';

            $users = User::query()
                ->where('is_active', true)
                ->whereDoesntHave('roles', fn ($query) => $query->where('name', 'Admin'))
                ->with(['attendances' => fn ($query) => $query->where('event_agenda_id', $eventAgenda->id)])
                ->orderBy($sortColumn, $direction)
                ->orderBy('name')
                ->get();
        }

        return $schema->columns(1)->components([
            Section::make('Detail Kegiatan')
                ->schema([
                    Placeholder::make('agenda_name')->label('Nama Kegiatan')->content(fn (): string => $eventAgenda?->name ?? '-'),
                    Placeholder::make('agenda_location')->label('Lokasi Kegiatan')->content(fn (): string => $eventAgenda?->location ?? '-'),
                    Placeholder::make('agenda_starts_at')->label('Mulai Kegiatan')->content(fn (): string => $eventAgenda?->starts_at?->format('d M Y H:i') ?? '-'),
                    Placeholder::make('agenda_ends_at')->label('Selesai Kegiatan')->content(fn (): string => $eventAgenda?->ends_at?->format('d M Y H:i') ?? '-'),
                    Placeholder::make('agenda_type')->label('Jenis Kegiatan')->content(fn (): string => ucfirst($eventAgenda?->type ?? '-')),
                    Placeholder::make('agenda_organizer')->label('Penyelenggara')->content(fn (): string => $eventAgenda?->organizer ?? '-'),
                ])
                ->columnSpanFull(),
            Html::make(fn (): string => view('filament.admin.attendance-detail-users', [
                    'eventAgenda' => $eventAgenda,
                    'users' => $users,
                ])->render())
                ->columnSpanFull(),
        ]);
    }

    private function ensurePresensiQrIsGenerated(EventAgenda $eventAgenda): void
    {
        if (blank($eventAgenda->qr_token)) {
            $eventAgenda->qr_token = (string) Str::uuid();
        }

        $qrFileMissing = blank($eventAgenda->qr_code_path)
            || ! File::exists(public_path('storage/'.$eventAgenda->qr_code_path));

        if ($qrFileMissing) {
            $relativePath = sprintf(
                'qr_presensi/%s_%s.svg',
                now()->format('Ymd_His'),
                Str::slug($eventAgenda->name),
            );

            $eventAgenda->qr_code_path = app(QrCodeService::class)->generateSvg(
                rtrim(config('app.url'), '/') . URL::temporarySignedRoute(
                    'attendance.scan',
                    now()->addHours(6),
                    [
                        'eventAgenda' => $eventAgenda->slug,
                        'token' => $eventAgenda->qr_token,
                    ],
                    false,
                ),
                $relativePath,
            );
        }

        $eventAgenda->saveQuietly();
    }
}
