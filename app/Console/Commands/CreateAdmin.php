<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin';

    protected $description = 'Membuat akun Admin awal';

    public function handle()
    {
        $name = $this->ask('Nama Admin');
        $email = $this->ask('Email Admin');
        $password = $this->secret('Password Admin');

        if (User::where('email', $email)->exists()) {
            $this->error('Email tersebut sudah digunakan.');
            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->info('Akun Admin berhasil dibuat.');

        return self::SUCCESS;
    }
}
