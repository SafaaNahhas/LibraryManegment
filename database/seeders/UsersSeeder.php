<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'safaa',
            'email' => 'safaa@gmail.com',
            'password' => Hash::make(12345678),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'sa',
            'email' => 'sa@gmail.com',
            'password' => Hash::make(12345678),
            'role' => 'user',
        ]);
    }
}
