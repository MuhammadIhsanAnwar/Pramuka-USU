<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

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

Route::get('/admin', function () {
    if (auth()->check()) {
        return redirect('/admin/users');
    }

    return redirect('/admin/login');
})->name('admin.home');

// Compatibility route for Filament navigation (some Filament versions expect this named route)
Route::get('/_filament_dashboard', function () {
	return redirect('/admin');
})->name('filament.admin.pages.dashboard');

// Fortify login routes fallback if package routes are unavailable
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->middleware('guest')->name('password.request');
Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => ['required', 'email']]);

    return back()->with('status', 'Jika email terdaftar, kami akan mengirim tautan reset password.');
})->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

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
