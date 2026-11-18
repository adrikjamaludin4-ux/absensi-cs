<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateCs extends Command
{
    protected $signature = 'app:create-cs';

    protected $description = 'Membuat akun Cleaning Service untuk pengujian';

    public function handle(): int
    {
        $name = $this->ask('Nama Cleaning Service');
        $email = $this->ask('Email Cleaning Service');

        if (User::where('email', $email)->exists()) {
            $this->error('Email tersebut sudah digunakan.');
            return self::FAILURE;
        }

        $password = $this->secret('Password Cleaning Service');

        if (empty($name) || empty($email) || empty($password)) {
            $this->error('Nama, email, dan password wajib diisi.');
            return self::FAILURE;
        }

        User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
            'role'     => 'cs',
        ]);

        $this->info('Akun Cleaning Service berhasil dibuat.');

        return self::SUCCESS;
    }
}