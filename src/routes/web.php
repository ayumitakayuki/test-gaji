<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AbsensiImportController;
use Livewire\Livewire;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SlipGajiExport;
use App\Exports\SlipGajiPdfExport;
use App\Http\Controllers\Mobile\KasbonController;
use App\Http\Controllers\Mobile\AuthController;

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

Route::get('/absensi/download-template', [AbsensiImportController::class, 'downloadTemplate'])->name('absensi.download-template');

Route::get('/export-absensi', function (Request $request) {
    $start_date = $request->query('start_date', now()->subMonth()->toDateString());
    $end_date = $request->query('end_date', now()->toDateString());
    $id_karyawan = $request->query('id_karyawan'); // ambil id karyawan dari query

    return Excel::download(new AbsensiExport($start_date, $end_date, $id_karyawan), 'absensi_karyawan.xlsx');
});

Route::get('/admin/slip-gaji/export/{id}', function ($id) {
    return Excel::download(new SlipGajiExport($id), 'slip-gaji-'.$id.'.xlsx');
})->name('slip-gaji.export');

Route::get('/slip-gaji/{id}/export-pdf', SlipGajiPdfExport::class)->name('slip-gaji.export.pdf');

Route::prefix('m')->group(function () {

    // ✅ halaman login khusus mobile (guest)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('m.login');
        Route::post('/login', [AuthController::class, 'login'])->name('m.login.post');
    });

    // ✅ logout mobile
    Route::post('/logout', [AuthController::class, 'logout'])->name('m.logout');

    // ✅ semua halaman mobile karyawan
    Route::middleware(['auth'])->group(function () {
        Route::get('/kasbon', [KasbonController::class, 'index'])->name('m.kasbon.index');
        Route::get('/kasbon/create', [KasbonController::class, 'create'])->name('m.kasbon.create');
        Route::post('/kasbon', [KasbonController::class, 'store'])->name('m.kasbon.store');
        Route::get('/kasbon/{id}', [KasbonController::class, 'show'])->name('m.kasbon.show');
    });
});

