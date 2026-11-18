<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\AttendanceLocationController;
use App\Http\Controllers\CsTaskController;
use App\Http\Controllers\CsRiwayatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Panel Administrator
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Tugas & Pengecekan Rutin (Admin)
    Route::get('/admin/tasks', [AdminTaskController::class, 'index'])->name('admin.tasks.index');
    Route::post('/admin/tasks', [AdminTaskController::class, 'store'])->name('admin.tasks.store');
    Route::put('/admin/tasks/{task}', [AdminTaskController::class, 'update'])->name('admin.tasks.update');
    Route::delete('/admin/tasks/{task}', [AdminTaskController::class, 'destroy'])->name('admin.tasks.destroy');
});

// Dashboard Satpam
Route::middleware(['auth', 'role:satpam'])->group(function () {
    Route::get('/dashboard-satpam', function () {
        return view('dashboard-satpam');
    })->name('dashboard.satpam');
});

// Panel Cleaning Service
Route::middleware(['auth', 'role:cs'])->group(function () {

    // Dashboard CS
    Route::get('/dashboard-cs', [AttendanceLocationController::class, 'dashboardCs'])
        ->name('dashboard.cs');

    // Halaman Absensi CS
    Route::get('/absensi-cs', [AttendanceLocationController::class, 'absensiCs'])
        ->name('absensi.cs');

    // Sistem Manajemen Tugas & Jadwal Kerja CS
    Route::get('/jadwal-kerja-cs', [CsTaskController::class, 'index'])
        ->name('jadwal.kerja.cs');

    Route::post('/cs/tasks/{task}/submit', [CsTaskController::class, 'submit'])
        ->name('cs.tasks.submit');

    // Riwayat Presensi CS (halaman terpisah)
    Route::get('/riwayat-absensi-cs', [CsRiwayatController::class, 'index'])
        ->name('riwayat.absensi.cs');

    // Pengajuan Izin / Sakit / Keperluan Mendesak CS
    Route::get('/izin-cs', [AttendanceLocationController::class, 'ajukanIzinView'])
        ->name('izin.cs');

    Route::post('/izin-cs', [AttendanceLocationController::class, 'ajukanIzinStore'])
        ->name('izin.cs.store');

    // Validasi GPS CS & Foto Selfie
    Route::post('/cs/check-location', [
        AttendanceLocationController::class,
        'checkLocation'
    ])->name('cs.check-location');

});

// Profil pengguna
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

// Autentikasi
require __DIR__ . '/auth.php';
