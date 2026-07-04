<?php

namespace App\Http\Controllers;

use App\Exports\ArrayReportExport;
use App\Models\Attendance;
use App\Models\EventAgenda;
use App\Models\NewsPost;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function newsPdf(): Response
    {
        return $this->pdfResponse('Laporan Berita', $this->newsRows(), ['Judul', 'Kategori', 'Penulis', 'Status', 'Publish', 'Viewer']);
    }

    public function newsExcel()
    {
        return Excel::download(new ArrayReportExport($this->newsRows(), ['Judul', 'Kategori', 'Penulis', 'Status', 'Publish', 'Viewer']), 'laporan-berita.xlsx');
    }

    public function agendaPdf(): Response
    {
        return $this->pdfResponse('Laporan Agenda', $this->agendaRows(), ['Nama', 'Lokasi', 'Status', 'Mulai', 'Dibuat Oleh']);
    }

    public function agendaExcel()
    {
        return Excel::download(new ArrayReportExport($this->agendaRows(), ['Nama', 'Lokasi', 'Status', 'Mulai', 'Dibuat Oleh']), 'laporan-agenda.xlsx');
    }

    public function attendancePdf(): Response
    {
        return $this->pdfResponse('Laporan Presensi', $this->attendanceRows(), ['User', 'Agenda', 'Status', 'Waktu Scan', 'Latitude', 'Longitude']);
    }

    public function attendanceExcel()
    {
        return Excel::download(new ArrayReportExport($this->attendanceRows(), ['User', 'Agenda', 'Status', 'Waktu Scan', 'Latitude', 'Longitude']), 'laporan-presensi.xlsx');
    }

    public function userPdf(): Response
    {
        return $this->pdfResponse('Laporan User', $this->userRows(), ['Nama', 'Email', 'Role', 'Jenis User', 'Aktif']);
    }

    public function userExcel()
    {
        return Excel::download(new ArrayReportExport($this->userRows(), ['Nama', 'Email', 'Role', 'Jenis User', 'Aktif']), 'laporan-user.xlsx');
    }

    private function pdfResponse(string $title, array $rows, array $headers): Response
    {
        $pdf = Pdf::loadView('reports.table', [
            'title' => $title,
            'headers' => $headers,
            'rows' => $rows,
        ])->setPaper('a4', 'landscape');

        return $pdf->download(strtolower(str_replace(' ', '-', $title)).'.pdf');
    }

    /**
     * @return array<int, array<int, string|int|null>>
     */
    private function newsRows(): array
    {
        return NewsPost::query()
            ->with(['category', 'author'])
            ->latest()
            ->get()
            ->map(static fn (NewsPost $newsPost): array => [
                $newsPost->title,
                $newsPost->category?->name,
                $newsPost->author?->name,
                $newsPost->status,
                $newsPost->published_at?->format('d M Y'),
                $newsPost->viewer_count,
            ])
            ->all();
    }

    /**
     * @return array<int, array<int, string|int|null>>
     */
    private function agendaRows(): array
    {
        return EventAgenda::query()
            ->with('creator')
            ->latest('starts_at')
            ->get()
            ->map(static fn (EventAgenda $agenda): array => [
                $agenda->name,
                $agenda->location,
                $agenda->status,
                $agenda->starts_at?->format('d M Y H:i'),
                $agenda->creator?->name,
            ])
            ->all();
    }

    /**
     * @return array<int, array<int, string|int|null>>
     */
    private function attendanceRows(): array
    {
        return Attendance::query()
            ->with(['user', 'agenda'])
            ->latest('scanned_at')
            ->get()
            ->map(static fn (Attendance $attendance): array => [
                $attendance->user?->name,
                $attendance->agenda?->name,
                $attendance->status,
                $attendance->scanned_at?->format('d M Y H:i'),
                $attendance->latitude,
                $attendance->longitude,
            ])
            ->all();
    }

    /**
     * @return array<int, array<int, string|int|null>>
     */
    private function userRows(): array
    {
        return User::query()
            ->with('roles')
            ->latest()
            ->get()
            ->map(static fn (User $user): array => [
                $user->name,
                $user->email,
                $user->roles->pluck('name')->join(', '),
                $user->jenis_user?->value ?? $user->jenis_user,
                $user->is_active ? 'Ya' : 'Tidak',
            ])
            ->all();
    }
}