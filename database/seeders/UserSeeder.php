<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        

        
        User::create([
            'name' => 'Admin Barbershop',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), 
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);

        
        User::create([
            'name' => 'Pelanggan Terdaftar',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'), 
            'email_verified_at' => now(),
            'role' => 'user',
        ]);

        
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'user',
        ]);
    }
}