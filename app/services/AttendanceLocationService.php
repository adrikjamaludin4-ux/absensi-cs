<?php

namespace App\Services;

class AttendanceLocationService
{
    public function checkLocation(
        float $latitude,
        float $longitude,
        float $accuracy
    ): array {
        $officeLatitude = (float) config('attendance.latitude');
        $officeLongitude = (float) config('attendance.longitude');
        $radius = (int) config('attendance.radius');
        $maxAccuracy = (int) config('attendance.max_accuracy');

        // Hitung jarak terlebih dahulu menggunakan rumus Haversine
        $earthRadius = 6371000;

        $lat1 = deg2rad($officeLatitude);
        $lat2 = deg2rad($latitude);

        // Perbaikan operator minus (-) yang terpotong
        $deltaLat = deg2rad($latitude - $officeLatitude);
        $deltaLon = deg2rad($longitude - $officeLongitude);

        $a = sin($deltaLat / 2) ** 2
            + cos($lat1) * cos($lat2)
            * sin($deltaLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        // Periksa radius kantor terlebih dahulu atau berikan toleransi akurasi 
        // jika user benar-benar berada sangat dekat dengan titik kantor.
        $isWithinRadius = $distance <= $radius;

        // Validasi akurasi GPS (opsional: abaikan jika jarak sudah sangat valid/dekat di dalam radius)
        if ($accuracy > $maxAccuracy && !$isWithinRadius) {
            return [
                'valid' => false,
                'distance' => round($distance, 2),
                'message' => 'Akurasi GPS kurang baik. Silakan coba kembali di tempat terbuka.',
            ];
        }

        return [
            'valid' => $isWithinRadius,
            'distance' => round($distance, 2),
            'message' => $isWithinRadius
                ? 'Lokasi berhasil diverifikasi.'
                : 'Anda berada di luar area absensi SAMSAT Ponorogo.',
        ];
    }
}