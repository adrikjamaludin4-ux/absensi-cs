<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Administrator
        User::updateOrCreate(
            ['name' => 'Administrator'],
            [
                'email'    => 'admin@samsat.local',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // Cleaning Service
        User::updateOrCreate(
            ['name' => 'Cleaning Service'],
            [
                'email'    => 'cs@samsat.local',
                'password' => Hash::make('cs123'),
                'role'     => 'cs',
            ]
        );
    }
}
