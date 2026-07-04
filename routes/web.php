<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/tentang-kami', [PublicController::class, 'about'])->name('about');
Route::get('/sejarah', [PublicController::class, 'history'])->name('history');
Route::get('/visi-misi', [PublicController::class, 'visionMission'])->name('vision-mission');
Route::get('/struktur-organisasi', [PublicController::class, 'structure'])->name('structure');
Route::get('/berita', [PublicController::class, 'newsIndex'])->name('news.index');
Route::get('/berita/{slug}', [PublicController::class, 'newsShow'])->name('news.show');
Route::get('/agenda', [PublicController::class, 'agendaIndex'])->name('agenda.index');
Route::get('/galeri', [PublicController::class, 'galleryIndex'])->name('gallery.index');
Route::get('/kontak', [PublicController::class, 'contact'])->name('contact');

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
