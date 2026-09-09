<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\IncomingLetterController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PublicController;
use App\Models\EventAgenda;
use App\Models\PresensiSession;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RouteController;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

// Redirect legacy Filament login URLs to the unified public login page
Route::redirect('/user/login', '/login')->name('user.login.redirect');
Route::redirect('/admin/login', '/login')->name('admin.login.redirect');

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
    Route::get('/surat-masuk', [PublicController::class, 'suratMasuk'])->name('surat-masuk');
    Route::get('/member/{uuid}', [\App\Http\Controllers\MemberController::class, 'show'])->name('member.show');
});

// Redirect legacy `/artisan` path to the legacy login entrypoint.
Route::redirect('/artisan', '/artisan/login.php');

// Incoming letters are managed by Filament resource pages. Add a compatibility
// redirect so legacy links to `/admin/surat-masuk` open the Filament CRUD index.
Route::middleware(['auth', 'role:Admin'])->group(function (): void {
	Route::get('/admin/surat-masuk', [RouteController::class, 'redirectAdminSuratMasuk']);
});

// Simple route to show current user's QR
Route::middleware('auth')->get('/user/qr', function () {
    return view('filament.user.show-qr-page');
})->name('user.qr');

// Legacy redirect: keep `/dashboard` pointing to the user panel at `/user`.
Route::get('/dashboard', [RouteController::class, 'redirectDashboard']);

// Redirect `/artisan` requests to the legacy Artisan login entrypoint.
Route::redirect('/artisan', '/artisan/login.php');

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
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Helper route: set the intended URL in session and redirect to the login page.
Route::get('/login/with-intended', function (Request $request) {
    $intended = $request->query('intended', url()->full());
    $request->session()->put('url.intended', $intended);

    return redirect()->route('login');
})->name('login.with_intended');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:web')
    ->name('logout');

Route::get('/logout', function (Request $request) {
    if (Auth::check()) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    return redirect('/');
});

// Allow scanning QR links without authentication so mobile users can open the
// presensi page and be prompted to log in. Submission routes remain protected.
Route::middleware([ValidateSignature::relative()])->group(function (): void {
    Route::get('/presensi/{eventAgenda}/{token}', [AttendanceController::class, 'scan'])
        ->name('attendance.scan');

    Route::get('/presensi/session/{presensiSession}/{token}', [PresensiController::class, 'scan'])
        ->name('presensi.scan');
});

Route::middleware(['auth', ValidateSignature::relative()])->group(function (): void {
    Route::post('/presensi/{eventAgenda}/{token}', [AttendanceController::class, 'submit'])
        ->name('attendance.submit');

    // GPS-based attendance (from user dashboard modal)
    Route::post('/user/presensi/gps', [AttendanceController::class, 'createViaGps'])
        ->name('user.presensi.gps');

    Route::post('/presensi/session/{presensiSession}/{token}', [PresensiController::class, 'submit'])
        ->name('presensi.submit');
});

Route::middleware(['auth', 'role:Admin'])->group(function (): void {
    Route::get('/admin/presensi/{eventAgenda}/scan-user', function (EventAgenda $eventAgenda) {
        $relative = URL::temporarySignedRoute(
            'attendance.scan',
            now()->addHours(6),
            [
                'eventAgenda' => $eventAgenda->slug,
                'token' => $eventAgenda->qr_token,
            ],
            false,
        );

        $url = rtrim(config('app.url'), '/') . $relative;

        return view('filament.admin.event-agenda-scan-user', compact('eventAgenda', 'url'));
    })->name('admin.attendance.scan-user');

    Route::get('/admin/presensi/session/{presensiSession}/scan-user', function (PresensiSession $presensiSession) {
        $relative = URL::temporarySignedRoute(
            'presensi.scan',
            now()->addHours(6),
            [
                'presensiSession' => $presensiSession->id,
                'token' => $presensiSession->qr_token,
            ],
            false,
        );

        $url = rtrim(config('app.url'), '/') . $relative;

        return view('filament.admin.presensi-session-scan-user', compact('presensiSession', 'url'));
    })->name('admin.presensi.scan-user');

    Route::post('/admin/presensi/session/{presensiSession}/status', [PresensiController::class, 'adminUpdateStatus'])
        ->name('admin.presensi.update-status');

    Route::post('/admin/presensi/{eventAgenda}/status', [AttendanceController::class, 'adminUpdateStatus'])
        ->name('admin.attendance.update-status');

    Route::prefix('laporan')->group(function (): void {
        Route::get('/berita/pdf', [ReportController::class, 'newsPdf'])->name('reports.news.pdf');
        Route::get('/berita/excel', [ReportController::class, 'newsExcel'])->name('reports.news.excel');
        Route::get('/agenda/pdf', [ReportController::class, 'agendaPdf'])->name('reports.agenda.pdf');
        Route::get('/agenda/excel', [ReportController::class, 'agendaExcel'])->name('reports.agenda.excel');
        Route::get('/presensi/pdf', [ReportController::class, 'attendancePdf'])->name('reports.attendance.pdf');
        Route::get('/presensi/excel', [ReportController::class, 'attendanceExcel'])->name('reports.attendance.excel');
        Route::get('/presensi/rekap/pdf', [ReportController::class, 'attendanceRecapPdf'])->name('reports.attendance_recap.pdf');
        Route::get('/presensi/rekap/excel', [ReportController::class, 'attendanceRecapExcel'])->name('reports.attendance_recap.excel');
        Route::get('/user/pdf', [ReportController::class, 'userPdf'])->name('reports.user.pdf');
        Route::get('/user/excel', [ReportController::class, 'userExcel'])->name('reports.user.excel');
        Route::get('/user/data/pdf', [ReportController::class, 'userDataPdf'])->name('reports.userdata.pdf');
        Route::get('/user/data/excel', [ReportController::class, 'userDataExcel'])->name('reports.userdata.excel');
    });
});
