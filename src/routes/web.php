<?php

use App\Exports\AbsensiExport;
use App\Exports\SlipGajiExport;
use App\Exports\SlipGajiPdfExport;
use App\Http\Controllers\AbsensiImportController;
use App\Http\Controllers\Mobile\AbsensiController;
use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\DashboardController;
use App\Http\Controllers\Mobile\KasbonController;
use App\Http\Controllers\Mobile\PasswordController;
use App\Http\Controllers\Mobile\SlipGajiController;
use App\Http\Controllers\Mobile\PerizinanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Maatwebsite\Excel\Facades\Excel;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/absensi/download-template', [AbsensiImportController::class, 'downloadTemplate'])
    ->name('absensi.download-template');

Route::get('/export-absensi', function (Request $request) {
    $start_date = $request->query('start_date', now()->subMonth()->toDateString());
    $end_date = $request->query('end_date', now()->toDateString());
    $id_karyawan = $request->query('id_karyawan'); // ambil id karyawan dari query

    return Excel::download(new AbsensiExport($start_date, $end_date, $id_karyawan), 'absensi_karyawan.xlsx');
});

Route::get('/admin/slip-gaji/export/{id}', function ($id) {
    return Excel::download(new SlipGajiExport($id), 'slip-gaji-' . $id . '.xlsx');
})->name('slip-gaji.export');

Route::get('/slip-gaji/{id}/export-pdf', SlipGajiPdfExport::class)
    ->name('slip-gaji.export.pdf');

/**
 * ✅ FIX: Laravel middleware auth default mencari route name "login"
 * Karena kamu pakai login mobile "m.login", kita buat alias "login" => redirect ke m.login
 */
Route::get('/login', function () {
    return redirect()->route('m.login');
})->name('login');

Route::prefix('m')->group(function () {

    // ✅ halaman login khusus mobile (guest)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('m.login');
        Route::post('/login', [AuthController::class, 'login'])->name('m.login.post');
    });

    // ✅ logout mobile (boleh kamu kasih middleware auth juga, tapi ini tetap jalan)
    Route::post('/logout', [AuthController::class, 'logout'])->name('m.logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('m.dashboard');
    // ✅ semua halaman mobile karyawan
    Route::middleware(['auth'])->group(function () {

        // ====== KASBON ======
        Route::get('/kasbon', [KasbonController::class, 'index'])->name('m.kasbon.index');
        Route::get('/kasbon/create', [KasbonController::class, 'create'])->name('m.kasbon.create');
        Route::post('/kasbon', [KasbonController::class, 'store'])->name('m.kasbon.store');
        Route::get('/kasbon/{id}', [KasbonController::class, 'show'])->name('m.kasbon.show');

        // ====== ABSENSI (SELFIE PROOF + LOCATION + PERIZINAN) ======
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('m.absensi.index');
        Route::post('/absensi/check', [AbsensiController::class, 'check'])->name('m.absensi.check');
        Route::get('/absensi/history', [AbsensiController::class, 'history'])->name('m.absensi.history');
        Route::get('/perizinan', [PerizinanController::class, 'index'])->name('m.perizinan.index');
        Route::get('/perizinan/create', [PerizinanController::class, 'create'])->name('m.perizinan.create');
        Route::post('/perizinan', [PerizinanController::class, 'store'])->name('m.perizinan.store');
        Route::post('/perizinan/{id}/approve', [PerizinanController::class, 'approve'])->name('m.perizinan.approve');

        // ====== SLIP GAJI KARYAWAN ======
        Route::get('/slip-gaji', [SlipGajiController::class, 'index'])->name('m.slip.index');
        Route::get('/slip-gaji/{id}', [SlipGajiController::class, 'show'])->name('m.slip.show');
        Route::get('/slip-gaji/{id}/pdf', [SlipGajiController::class, 'pdf'])->name('m.slip.pdf');

        Route::get('/password', [PasswordController::class, 'edit'])->name('m.password.edit');
        Route::post('/password', [PasswordController::class, 'update'])->name('m.password.update');
        
        Route::middleware(['auth','role:admin'])->group(function () {
            Route::post('/absensi/{absensi}/approve', [AbsensiController::class, 'approve'])->name('absensi.approve');
        });
    });
});