<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'username' => 'Admin Sarpras',
            'email' => 'admin@sarpras.com',
            'password' => Hash::make('Admin123'),
            'role' => 'admin',
            'origin' => 'Sarpras System',
        ]);
    }
}
