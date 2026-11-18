<?php

use App\Http\Controllers\AttendanceLocationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard Admin
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Dashboard Satpam
Route::middleware(['auth', 'role:satpam'])->group(function () {
    Route::get('/dashboard-satpam', function () {
        return view('dashboard-satpam');
    })->name('dashboard.satpam');
});

// Halaman Cleaning Service
Route::middleware(['auth', 'role:cs'])->group(function () {

    // Dashboard CS
    Route::get('/dashboard-cs', function () {
        return view('dashboard-cs');
    })->name('dashboard.cs');

    // Halaman Absensi CS
    Route::get('/absensi-cs', function () {
        return view('absensi-cs');
    })->name('absensi.cs');

    // Halaman Jadwal Kerja CS
    Route::get('/jadwal-kerja-cs', function () {
        return view('jadwal-kerja-cs');
    })->name('jadwal.kerja.cs');


    // Validasi GPS CS
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


// Ini Irham
// hallobosku