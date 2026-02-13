<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bspz.com'],
            [
                'name' => 'Administator',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
