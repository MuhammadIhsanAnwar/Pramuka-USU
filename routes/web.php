<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\IncomingLetterController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

Route::middleware(['maintenance'])->group(function (): void {
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::redirect('/home', '/');
    Route::get('/tentang-kami', [PublicController::class, 'about'])->name('about');
    Route::get('/sejarah', [PublicController::class, 'history'])->name('history');
    Route::get('/visi-misi', [PublicController::class, 'visionMission'])->name('vision-mission');
    Route::get('/struktur-organisasi', [PublicController::class, 'structure'])->name('structure');
    Route::get('/berita', [PublicController::class, 'newsIndex'])->name('news.index');
    Route::get('/berita/{slug}', [PublicController::class, 'newsShow'])->name('news.show');
    Route::get('/agenda', [PublicController::class, 'agendaIndex'])->name('agenda.index');
    Route::get('/agenda/{eventAgenda}', [PublicController::class, 'agendaShow'])->name('agenda.show');
    Route::get('/galeri', [PublicController::class, 'galleryIndex'])->name('gallery.index');
    Route::get('/galeri/{gallery}', [PublicController::class, 'galleryShow'])->name('gallery.show');
    Route::get('/kontak', [PublicController::class, 'contact'])->name('contact');
    Route::get('/surat-masuk', function () {
        return view('public.surat-masuk');
    })->name('surat-masuk');
});

// Incoming letters are managed by Filament resource pages. Add a compatibility
// redirect so legacy links to `/admin/surat-masuk` open the Filament CRUD index.
Route::middleware(['auth', 'role:Admin'])->group(function (): void {
	Route::get('/admin/surat-masuk', function () {
		return redirect()->route('filament.admin.resources.incoming-letters.index');
	});
});

// Legacy redirect: keep `/dashboard` pointing to the user panel at `/user`.
Route::get('/dashboard', static function () {
    return redirect()->to(url('/user'));
});

// (Removed compatibility route to avoid duplicate route name with Filament.)

// Fortify registers these routes automatically when its views are enabled.
// Keeping the public endpoints here ensures their names remain available when a
// production configuration cache disables those automatic view routes.
Route::middleware('guest:web')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:web')
    ->name('logout');

Route::middleware('auth')->group(function (): void {
	Route::get('/presensi/{eventAgenda}/{token}', [AttendanceController::class, 'scan'])->name('attendance.scan');
});

Route::middleware(['auth', 'role:Admin'])->prefix('laporan')->group(function (): void {
	Route::get('/berita/pdf', [ReportController::class, 'newsPdf'])->name('reports.news.pdf');
	Route::get('/berita/excel', [ReportController::class, 'newsExcel'])->name('reports.news.excel');
	Route::get('/agenda/pdf', [ReportController::class, 'agendaPdf'])->name('reports.agenda.pdf');
	Route::get('/agenda/excel', [ReportController::class, 'agendaExcel'])->name('reports.agenda.excel');
	Route::get('/presensi/pdf', [ReportController::class, 'attendancePdf'])->name('reports.attendance.pdf');
	Route::get('/presensi/excel', [ReportController::class, 'attendanceExcel'])->name('reports.attendance.excel');
	Route::get('/user/pdf', [ReportController::class, 'userPdf'])->name('reports.user.pdf');
	Route::get('/user/excel', [ReportController::class, 'userExcel'])->name('reports.user.excel');
});
