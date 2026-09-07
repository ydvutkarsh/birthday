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
            ['username' => 'omsha'],
            [
                'name' => 'Omsha',
                'email' => 'omsha@example.com',
                'password' => Hash::make('om@123'),
                'role' => 'guest',
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['username' => 'akash'],
            [
                'name' => 'Akash',
                'email' => 'akash@example.com',
                'password' => Hash::make('akash0909'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );
    }
}