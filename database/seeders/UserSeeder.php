<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Max Mustermann',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Employee user
        User::updateOrCreate(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Hans Meier',
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]
        );
    }
}
