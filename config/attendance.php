
<?php

return [
    'latitude' => env('ABSENSI_LATITUDE', -7.868279888411077),

    'longitude' => env('ABSENSI_LONGITUDE', 111.46199600424778),

    'radius' => (int) env('ABSENSI_RADIUS', 50),

    'max_accuracy' => (int) env('ABSENSI_MAX_ACCURACY', 30),
];
