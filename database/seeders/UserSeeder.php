<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Superadmin
        User::create([
            'name' => 'Keandra',
            'email' => 'keandra@gmail.com',
            'password' => Hash::make('superadmin'),
            'role' => 'superadmin', // <-- ini penting
        ]);

        // User biasa
        User::create([
            'name' => 'Budi',
            'email' => 'kenmotor@gmail.com',
            'password' => Hash::make('kenmotor99'),
            'role' => 'user', // default kalau mau
        ]);
    }
}
