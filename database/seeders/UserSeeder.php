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
        // Membuat User dengan peran Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@app.com',
            'password' => Hash::make('password'), // password default: password
        ]);
        $admin->assignRole('Admin');

        // Membuat User dengan peran Analyst
        $analyst = User::create([
            'name' => 'Analyst User',
            'email' => 'analyst@app.com',
            'password' => Hash::make('password'),
        ]);
        $analyst->assignRole('Analyst');

        // Membuat User dengan peran User
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@app.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('User');
    }
}
