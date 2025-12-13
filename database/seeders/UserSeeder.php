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

        User::create([
            'name' => 'chairil',
            'email' => 'chairilsyahrain24@gmail.com',
            'password' => Hash::make('superadmin'),
            'role' => 'superadmin', // <-- ini penting
        ]);

        // User biasa
        User::create([
            'name' => 'Ken Motor',
            'email' => 'kenmotor@gmail.com',
            'password' => Hash::make('kenmotor99'),
            'role' => 'user', // default kalau mau
        ]);

        User::create([
            'name' => 'Admin Showroom',
            'email' => 'showroom@gmail.com',
            'password' => Hash::make('adminshowroom'),
            'role' => 'adminshowroom',
        ]);
    }
}
