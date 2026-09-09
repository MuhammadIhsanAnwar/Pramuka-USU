<?php

namespace App\Filament\Admin\Resources\PresensiSessionResource\Pages;

use App\Filament\Admin\Resources\PresensiSessionResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewPresensiSession extends ViewRecord
{
    protected static string $resource = PresensiSessionResource::class;
    protected static ?string $title = 'Detail Presensi';

    protected function getHeaderActions(): array
    {
        $session = $this->getRecord();

        return [
            Action::make('scanUser')
                ->label('Scan QR User')
                ->url(fn (): string => rtrim(config('app.url'), '/') . route('admin.presensi.scan-user', $session, false))
                ->openUrlInNewTab(),
            Action::make('viewQr')
                ->label('Munculkan QR Presensi')
                ->modalHeading('QR Presensi')
                ->modalContent(function () use ($session) {
                    if (filled($session->qr_token)) {
                        return view('filament.admin.presensi-session-qr', [
                            'session' => $session,
                            'signedUrl' => rtrim(config('app.url'), '/') . route('presensi.scan', [
                                'presensiSession' => $session,
                                'token' => $session->qr_token,
                            ], false),
                        ]);
                    }

                    return new \Illuminate\Support\HtmlString('<div class="p-6 text-sm text-slate-700">QR Presensi belum dihasilkan untuk sesi ini.</div>');
                }),
        ];
    }

    public function form(Schema $schema): Schema
    {
        $session = $this->getRecord();
        $users = collect();

        if ($session) {
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
                ->whereIn('jenis_user', [
                    \App\Enums\UserKind::Pembina->value,
                    \App\Enums\UserKind::PesertaDidik->value,
                    \App\Enums\UserKind::Tamu->value,
                ])
                ->with(['presensiRecords' => fn ($query) => $query->where('presensi_session_id', $session->id)])
                ->orderBy($sortColumn, $direction)
                ->orderBy('name')
                ->get();
        }

        return $schema->columns(1)->components([
            Section::make('Detail Presensi')
                ->schema([
                    Placeholder::make('session_name')->label('Nama Presensi')->content(fn (): string => $session?->name ?? '-'),
                    Placeholder::make('session_location')->label('Lokasi Presensi')->content(fn (): string => $session?->location ?? '-'),
                    Placeholder::make('session_radius')->label('Radius')->content(fn (): string => $session?->radius ? $session->radius.' m' : '-'),
                    Placeholder::make('session_starts_at')->label('Mulai Presensi')->content(fn (): string => $session?->starts_at?->format('d M Y H:i') ?? '-'),
                    Placeholder::make('session_ends_at')->label('Batas Presensi')->content(fn (): string => $session?->ends_at?->format('d M Y H:i') ?? '-'),
                    Placeholder::make('session_status')->label('Status')->content(fn (): string => ucfirst($session?->status ?? '-')),
                ])
                ->columnSpanFull(),
            Html::make(fn (): string => view('filament.admin.presensi-session-detail-users', [
                    'session' => $session,
                    'users' => $users,
                ])->render())
                ->columnSpanFull(),
        ]);
    }
}
