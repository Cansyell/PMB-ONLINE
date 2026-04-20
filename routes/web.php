<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PendaftaranController as AdminPendaftaranController;
use App\Http\Controllers\Mahasiswa\PendaftaranController as MahasiswaPendaftaranController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MonitoringController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
     ->middleware(['auth', 'verified'])
     ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Wilayah API ────────────────────────────────────────────────────────────
Route::prefix('api/wilayah')->name('api.wilayah.')->group(function () {
    Route::get('cities/{province_code}',   [WilayahController::class, 'cities'])->name('cities');
    Route::get('districts/{city_code}',    [WilayahController::class, 'districts'])->name('districts');
    Route::get('villages/{district_code}', [WilayahController::class, 'villages'])->name('villages');
});

// ── Admin ──────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('pendaftaran', AdminPendaftaranController::class)
         ->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::resource('users', UserController::class);

    Route::get('/monitoring', [MonitoringController::class, 'index'])
        ->name('monitoring.index');

    // Endpoint AJAX - oleh JS di frontend
    Route::get('/monitoring/metrics', [MonitoringController::class, 'metrics'])
        ->name('monitoring.metrics');
});

// ── Mahasiswa ──────────────────────────────────────────────────────────────
Route::prefix('mahasiswa')->name('mahasiswa.')->middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('pendaftaran',           [MahasiswaPendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get('pendaftaran/create',    [MahasiswaPendaftaranController::class, 'create'])->name('pendaftaran.create');
    Route::get('pendaftaran/edit',      [MahasiswaPendaftaranController::class, 'edit'])->name('pendaftaran.edit');
    Route::post('pendaftaran',          [MahasiswaPendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::put('pendaftaran',           [MahasiswaPendaftaranController::class, 'update'])->name('pendaftaran.update');
    Route::get('pendaftaran/cetak-pdf', [MahasiswaPendaftaranController::class, 'cetakPdf'])->name('pendaftaran.cetak-pdf');
});

require __DIR__.'/auth.php';