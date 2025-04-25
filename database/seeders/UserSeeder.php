<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '081234567890',
                'saldo' => 100000,
                'point' => 50,
                'role' => 'user',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '082345678901',
                'saldo' => 200000,
                'point' => 100,
                'role' => 'user',
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '083456789012',
                'saldo' => 150000,
                'point' => 75,
                'role' => 'user',
            ],
            [
                'name' => 'Sarah White',
                'email' => 'sarah@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '084567890123',
                'saldo' => 50000,
                'point' => 25,
                'role' => 'user',
            ],
            [
                'name' => 'David Black',
                'email' => 'david@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '085678901234',
                'saldo' => 300000,
                'point' => 150,
                'role' => 'admin',
            ],
        ];

        User::insert($users);
    }
}
