<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSatpam extends Command
{
    protected $signature = 'app:create-satpam';

    protected $description = 'Membuat akun Satpam untuk pengujian';

    public function handle(): int
    {
        $name = $this->ask('Nama Satpam');
        $email = $this->ask('Email Satpam');

        if (User::where('email', $email)->exists()) {
            $this->error('Email tersebut sudah digunakan.');
            return self::FAILURE;
        }

        $password = $this->secret('Password Satpam');

        if (empty($password)) {
            $this->error('Password tidak boleh kosong.');
            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'satpam',
        ]);

        $this->info('Akun Satpam berhasil dibuat.');

        return self::SUCCESS;
    }
}
