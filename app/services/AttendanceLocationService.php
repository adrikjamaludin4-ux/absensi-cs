<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class AttendanceLocationService
{
    /**
     * Memvalidasi koordinat lokasi dan radius dari titik pusat kantor.
     */
    public function checkLocation(
        float $latitude,
        float $longitude,
        float $accuracy
    ): array {
        $officeLatitude = (float) config('attendance.latitude');
        $officeLongitude = (float) config('attendance.longitude');
        $radius = (int) config('attendance.radius');
        $maxAccuracy = (int) config('attendance.max_accuracy');

        // Rumus Haversine
        $earthRadius = 6371000;

        $lat1 = deg2rad($officeLatitude);
        $lat2 = deg2rad($latitude);

        $deltaLat = deg2rad($latitude - $officeLatitude);
        $deltaLon = deg2rad($longitude - $officeLongitude);

        $a = sin($deltaLat / 2) ** 2
            + cos($lat1) * cos($lat2)
            * sin($deltaLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        $isWithinRadius = $distance <= $radius;

        // Validasi akurasi GPS jika berada di luar batas toleransi
        if ($accuracy > $maxAccuracy && !$isWithinRadius) {
            return [
                'valid' => false,
                'distance' => round($distance, 2),
                'message' => 'Akurasi GPS kurang akurat. Silakan coba kembali di tempat terbuka.',
            ];
        }

        return [
            'valid' => $isWithinRadius,
            'distance' => round($distance, 2),
            'message' => $isWithinRadius
                ? 'Lokasi presensi berhasil diverifikasi.'
                : 'Anda berada di luar radius area presensi yang ditentukan.',
        ];
    }

    /**
     * Mengecek status waktu presensi secara dinamis berdasarkan jam kerja (WIB).
     *
     * Pembagian Jam Presensi:
     * 1. 05.00 - 05.30 WIB : Sesi Presensi Masuk
     * 2. 05.31 - 14.59 WIB : Jeda Presensi (Presensi Masuk Ditutup, Menunggu Presensi Pulang)
     * 3. 15.00 - 18.00 WIB : Sesi Presensi Pulang
     * 4. 18.01 - 04.59 WIB : Seluruh Sesi Presensi Hari Ini Telah Berakhir
     */
    public function getTimeStatusForUser(int $userId): array
    {
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $currentTime = $now->format('H:i');
        $currentMinutes = $now->hour * 60 + $now->minute;

        // Cek apakah ada pengajuan izin/ketidakhadiran hari ini
        $leaveRequest = LeaveRequest::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        // Cek apakah sudah ada rekaman absensi hari ini
        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        $restrictHours = config('attendance.restrict_hours', true);

        // Batas Menit (WIB)
        $mStart = 5 * 60;        // 05:00 = 300
        $mEnd   = 5 * 60 + 30;   // 05:30 = 330
        $pStart = 15 * 60;       // 15:00 = 900
        $pEnd   = 18 * 60;       // 18:00 = 1080

        $canClockIn = false;
        $canClockOut = false;
        $bannerType = 'info'; // 'active', 'waiting', 'expired', 'success', 'leave'
        $bannerTitle = '';
        $bannerMessage = '';
        $actionType = null; // 'masuk', 'pulang', 'menunggu_pulang', 'selesai', 'leave'

        // 1. Jika Petugas Mengajukan Izin/Sakit Hari Ini
        if ($leaveRequest) {
            return [
                'now_time' => $currentTime,
                'today_date' => $today,
                'action_type' => 'leave',
                'can_clock_in' => false,
                'can_clock_out' => false,
                'can_action' => false,
                'banner_type' => 'leave',
                'banner_title' => "Status Kehadiran: {$leaveRequest->kategori}",
                'banner_message' => "Anda tercatat tidak hadir hari ini dengan status {$leaveRequest->kategori}. Alasan: {$leaveRequest->alasan}",
                'attendance' => $attendance,
                'leave_request' => $leaveRequest,
                'masuk_range' => '05.00 - 05.30 WIB',
                'pulang_range' => '15.00 - 18.00 WIB',
            ];
        }

        // 2. Mode Bebas / Testing (Jika restrict_hours dinonaktifkan di .env)
        if (!$restrictHours) {
            if (!$attendance || !$attendance->clock_in) {
                $actionType = 'masuk';
                $canClockIn = true;
                $bannerType = 'active';
                $bannerTitle = 'Presensi Masuk Aktif (Mode Testing)';
                $bannerMessage = 'Silakan lakukan presensi masuk Anda sekarang.';
            } elseif (!$attendance->clock_out) {
                $actionType = 'pulang';
                $canClockOut = true;
                $bannerType = 'active';
                $bannerTitle = 'Presensi Pulang Aktif (Mode Testing)';
                $bannerMessage = 'Silakan lakukan presensi pulang Anda sekarang.';
            } else {
                $actionType = 'selesai';
                $bannerType = 'success';
                $bannerTitle = 'Presensi Hari Ini Selesai!';
                $bannerMessage = 'Anda telah menyelesaikan presensi masuk dan pulang hari ini.';
            }

            return [
                'now_time' => $currentTime,
                'today_date' => $today,
                'action_type' => $actionType,
                'can_clock_in' => $canClockIn,
                'can_clock_out' => $canClockOut,
                'can_action' => ($canClockIn || $canClockOut),
                'banner_type' => $bannerType,
                'banner_title' => $bannerTitle,
                'banner_message' => $bannerMessage,
                'attendance' => $attendance,
                'leave_request' => null,
                'masuk_range' => '05.00 - 05.30 WIB',
                'pulang_range' => '15.00 - 18.00 WIB',
            ];
        }

        // 3. Logika Normal Berdasarkan Jam Sekarang (WIB):

        // KASUS A: Pukul 05.00 - 05.30 WIB (Sesi Presensi Masuk)
        if ($currentMinutes >= $mStart && $currentMinutes <= $mEnd) {
            $actionType = 'masuk';
            if (!$attendance || !$attendance->clock_in) {
                $canClockIn = true;
                $bannerType = 'active';
                $bannerTitle = 'Waktu Presensi Masuk Aktif!';
                $bannerMessage = 'Silakan lakukan presensi masuk sekarang. Batas akhir presensi masuk pukul 05.30 WIB.';
            } else {
                $bannerType = 'success';
                $jamMasuk = substr($attendance->clock_in, 0, 5);
                $bannerTitle = "Presensi Masuk Berhasil Diterima ({$jamMasuk} WIB)";
                $bannerMessage = 'Selamat bertugas! Presensi pulang akan dibuka pukul 15.00 - 18.00 WIB.';
            }
        }
        // KASUS B: Pukul 05.31 - 14.59 WIB (Presensi Masuk Ditutup, Menunggu Presensi Pulang)
        elseif ($currentMinutes > $mEnd && $currentMinutes < $pStart) {
            $actionType = 'menunggu_pulang';
            if ($attendance && $attendance->clock_in) {
                $jamMasuk = substr($attendance->clock_in, 0, 5);
                $bannerType = 'waiting';
                $bannerTitle = "Presensi Masuk Tercatat ({$jamMasuk} WIB)";
                $bannerMessage = 'Waktu presensi masuk telah ditutup. Waktu presensi pulang akan dibuka pukul 15.00 WIB.';
            } else {
                $bannerType = 'expired';
                $bannerTitle = 'Presensi Masuk Telah Ditutup';
                $bannerMessage = 'Waktu presensi masuk (05.00 - 05.30 WIB) telah berakhir. Presensi pulang akan dibuka pukul 15.00 WIB.';
            }
        }
        // KASUS C: Pukul 15.00 - 18.00 WIB (Sesi Presensi Pulang)
        elseif ($currentMinutes >= $pStart && $currentMinutes <= $pEnd) {
            $actionType = 'pulang';
            if (!$attendance || !$attendance->clock_out) {
                $canClockOut = true;
                $bannerType = 'active';
                $bannerTitle = 'Waktu Presensi Pulang Telah Dibuka!';
                $bannerMessage = 'Silakan lakukan presensi pulang Anda sebelum pukul 18.00 WIB.';
            } else {
                $bannerType = 'success';
                $jamPulang = substr($attendance->clock_out, 0, 5);
                $bannerTitle = "Presensi Hari Ini Selesai ({$jamPulang} WIB)";
                $bannerMessage = 'Anda telah menyelesaikan presensi pulang. Terima kasih atas kerja keras Anda hari ini!';
            }
        }
        // KASUS D: Pukul 18.01 - 04.59 WIB (Seluruh Sesi Presensi Hari Ini Telah Berakhir)
        else {
            $actionType = 'selesai';
            if ($attendance && $attendance->clock_out) {
                $bannerType = 'success';
                $bannerTitle = 'Presensi Hari Ini Selesai!';
                $bannerMessage = 'Seluruh jam presensi hari ini telah berakhir. Terima kasih atas kerja keras Anda!';
            } elseif ($attendance && $attendance->clock_in) {
                $bannerType = 'expired';
                $bannerTitle = 'Seluruh Jam Presensi Hari Ini Telah Berakhir';
                $bannerMessage = 'Batas waktu presensi pulang (18.00 WIB) telah berakhir. Presensi berikutnya dibuka besok pukul 05.00 WIB.';
            } else {
                $bannerType = 'expired';
                $bannerTitle = 'Seluruh Jam Presensi Hari Ini Telah Berakhir';
                $bannerMessage = 'Seluruh jam operasional presensi hari ini telah berakhir. Presensi berikutnya dibuka besok pukul 05.00 WIB.';
            }
        }

        return [
            'now_time' => $currentTime,
            'today_date' => $today,
            'action_type' => $actionType,
            'can_clock_in' => $canClockIn,
            'can_clock_out' => $canClockOut,
            'can_action' => ($canClockIn || $canClockOut),
            'banner_type' => $bannerType,
            'banner_title' => $bannerTitle,
            'banner_message' => $bannerMessage,
            'attendance' => $attendance,
            'leave_request' => null,
            'masuk_range' => '05.00 - 05.30 WIB',
            'pulang_range' => '15.00 - 18.00 WIB',
        ];
    }
}
