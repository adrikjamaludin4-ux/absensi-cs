<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Administrator
        $admin = User::updateOrCreate(
            ['email' => 'admin@cs.local'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // Backup compatibility jika admin login dengan email lama
        User::updateOrCreate(
            ['email' => 'admin@samsat.local'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // 2. Akun Cleaning Service
        $cs = User::updateOrCreate(
            ['email' => 'cs@cs.local'],
            [
                'name'     => 'Petugas Cleaning Service',
                'password' => Hash::make('cs123'),
                'role'     => 'cs',
            ]
        );

        User::updateOrCreate(
            ['email' => 'cs@samsat.local'],
            [
                'name'     => 'Petugas Cleaning Service',
                'password' => Hash::make('cs123'),
                'role'     => 'cs',
            ]
        );

        // 3. Data Awal Jadwal Penugasan & Pengecekan Rutin
        $initialTasks = [
            [
                'title' => 'Pengecekan Kebersihan & Kelengkapan Toilet Lt. 1',
                'description' => 'Pastikan lantai kering, sabun dan tisu terisi, wastafel bersih, dan closet berfungsi baik.',
                'location' => 'Toilet Lantai 1',
                'interval_type' => 'harian',
                'target_time' => 'Setiap 2 Jam Sekali (08.00, 10.00, 12.00, 14.00, 16.00 WIB)',
                'assigned_to' => null, // Berlaku untuk semua CS
                'is_active' => true,
            ],
            [
                'title' => 'Pembersihan & Kerapian Ruang Meeting Zoom Lt. 2',
                'description' => 'Bersihkan meja rapat, rapikan kursi, kosongkan tempat sampah, dan pastikan ruangan beraroma segar.',
                'location' => 'Ruang Rapat Zoom Lt. 2',
                'interval_type' => 'harian',
                'target_time' => 'Setiap 2 Jam Sekali / Sebelum & Sesudah Rapat',
                'assigned_to' => null,
                'is_active' => true,
            ],
            [
                'title' => 'Pembersihan Kaca Pintu & Area Pelayanan Utama',
                'description' => 'Lap kaca pintu masuk, pel lantai area tunggu, dan bersihkan debu di meja pelayanan.',
                'location' => 'Lobby & Area Pelayanan',
                'interval_type' => 'harian',
                'target_time' => 'Pagi (07.00 WIB) & Siang (13.00 WIB)',
                'assigned_to' => null,
                'is_active' => true,
            ],
            [
                'title' => 'Pengecekan Tandon Air & Saluran Pembuangan',
                'description' => 'Pemeriksaan kebersihan tandon penampungan air dan pastikan saluran pembuangan luar tidak tersumbat.',
                'location' => 'Area Luar & Rooftop',
                'interval_type' => 'mingguan',
                'target_time' => 'Jumat Pagi (08.00 WIB)',
                'assigned_to' => null,
                'is_active' => true,
            ],
            [
                'title' => 'Pembersihan Total Plafon, Ventilasi, & Sudut Dinding',
                'description' => 'Pembersihan sarang laba-laba, debu penutup AC, dan sudut-sudut plafon tinggi di seluruh ruangan.',
                'location' => 'Seluruh Ruangan Kantor',
                'interval_type' => 'bulanan',
                'target_time' => 'Sabtu Minggu Terakhir (08.00 - 12.00 WIB)',
                'assigned_to' => null,
                'is_active' => true,
            ],
        ];

        foreach ($initialTasks as $taskData) {
            Task::updateOrCreate(
                ['title' => $taskData['title']],
                $taskData
            );
        }
    }
}
