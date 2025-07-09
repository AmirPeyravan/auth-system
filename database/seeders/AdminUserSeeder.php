<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['phone' => '09101740239'],
            [
                'password' => Hash::make('admin'),
                'is_admin' => true,
            ]
        );
    }
}
