
<?php

return [
    'latitude' => env('ABSENSI_LATITUDE', -7.868279888411077),

    'longitude' => env('ABSENSI_LONGITUDE', 111.46199600424778),

    'radius' => (int) env('ABSENSI_RADIUS', 50),

    'max_accuracy' => (int) env('ABSENSI_MAX_ACCURACY', 30),

    // Pembatasan Jam Presensi Cleaning Service (WIB)
    'restrict_hours' => env('ATTENDANCE_RESTRICT_HOURS', true),
    'masuk_start' => env('ATTENDANCE_MASUK_START', '05:00'),
    'masuk_end' => env('ATTENDANCE_MASUK_END', '05:30'),
    'pulang_start' => env('ATTENDANCE_PULANG_START', '15:00'),
    'pulang_end' => env('ATTENDANCE_PULANG_END', '18:00'),
];
