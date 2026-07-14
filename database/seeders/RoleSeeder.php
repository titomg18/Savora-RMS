<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar role sesuai permintaan
        $roles = [
            ['name' => 'Admin', 'role' => 'admin', 'email' => 'admin@example.com'],
            ['name' => 'Owner', 'role' => 'owner', 'email' => 'owner@example.com'],
            ['name' => 'Cashier', 'role' => 'cashier', 'email' => 'cashier@example.com'],
            ['name' => 'Waiter', 'role' => 'waiter', 'email' => 'waiter@example.com'],
            ['name' => 'Chef', 'role' => 'chef', 'email' => 'chef@example.com'],
        ];

        foreach ($roles as $data) {
            User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make('password'), // Password default
                'role'     => $data['role'],
            ]);
        }
    }
}