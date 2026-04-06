<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WbsOfficerSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'wbs@bspz.com'],
            [
                'name' => 'WBS Officer',
                'password' => Hash::make('12345678'),
                'role' => 'wbs_officer',
                'is_active' => true,
            ]
        );
    }
}
