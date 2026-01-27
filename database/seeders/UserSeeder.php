<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::create([
            'full_name' => 'Admin User',
            'phonenumber' => '1111111111',
            'role' => 'admin',
            'address' => '123 Admin Street, City, Country',
            'password' => Hash::make(value: 'password'),
        ]);

        // Create a municipality official
        User::create([
            'full_name' => 'Municipality Official',
            'phonenumber' => '2222222222',
            'role' => 'official',
            'address' => '456 Official Avenue, City, Country',
            'password' => Hash::make('password'),
        ]);

        // Create some citizen users using factory
        User::factory(10)->create();
    }
}
