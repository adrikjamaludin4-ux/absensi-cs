<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateCs extends Command
{
    protected $signature = 'app:create-cs';

    protected $description = 'Membuat akun Customer Service untuk pengujian';

    public function handle(): int
    {
        $name = $this->ask('Nama CS');
        $email = $this->ask('Email CS');

        if (User::where('email', $email)->exists()) {
            $this->error('Email tersebut sudah digunakan.');
            return self::FAILURE;
        }

        $password = $this->secret('Password CS');

        if (empty($name) || empty($email) || empty($password)) {
            $this->error('Nama, email, dan password wajib diisi.');
            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'cs',
        ]);

        $this->info('Akun CS berhasil dibuat.');

        return self::SUCCESS;
    }
}
