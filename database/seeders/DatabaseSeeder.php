<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat akun admin dengan password "admin123"
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com', 
            'password' => Hash::make('admin123'),
        ]);
    }
}
