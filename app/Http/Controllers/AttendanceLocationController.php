<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AttendanceLocationService;
use App\Models\Attendance;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceLocationController extends Controller
{
    public function checkLocation(
        Request $request,
        AttendanceLocationService $locationService
    ) {
        // Validasi data GPS dan Foto Selfie dari browser
        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['required', 'numeric', 'min:0'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Validasi file gambar maksimal 2MB
        ]);

        // Periksa lokasi petugas menggunakan service Haversine
        $result = $locationService->checkLocation(
            (float) $validated['latitude'],
            (float) $validated['longitude'],
            (float) $validated['accuracy']
        );

        // Jika lokasi tidak valid (di luar radius atau akurasi buruk), tolak presensi
        if (!$result['valid']) {
            return response()->json([
                'success' => false,
                'distance' => $result['distance'],
                'message' => $result['message'],
            ], 422);
        }

        // Simpan foto selfie ke folder public/attendances
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = 'cs_' . auth()->id() . '_' . time() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('attendances', $filename, 'public');
        }

        // Tentukan waktu sekarang (WIB / zona waktu lokal aplikasi)
        $today = Carbon::now()->toDateString();
        $nowTime = Carbon::now()->toTimeString();

        // Cek apakah user sudah melakukan absen masuk hari ini
        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            // Jika belum ada, catat sebagai Absen Masuk (Clock In)
            Attendance::create([
                'user_id' => auth()->id(),
                'date' => $today,
                'clock_in' => $nowTime,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'accuracy' => $validated['accuracy'],
                'distance' => $result['distance'],
                'photo_path' => $photoPath,
                'status_face' => 'Verified (Selfie)', // Placeholder verifikasi wajah sementara
                'status' => 'hadir',
            ]);

            $message = 'Absen masuk berhasil direkam dengan verifikasi GPS dan foto selfie!';
        } else {
            // Jika sudah absen masuk, update / catat sebagai Absen Pulang (Clock Out)
            $attendance->update([
                'clock_out' => $nowTime,
                'photo_path' => $photoPath ?? $attendance->photo_path,
            ]);

            $message = 'Absen pulang berhasil direkam!';
        }

        return response()->json([
            'success' => true,
            'distance' => $result['distance'],
            'message' => $message,
        ], 200);
    }
}