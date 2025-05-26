<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Receptionist;
use Illuminate\Support\Facades\Hash;

class ReceptionistSeeder extends Seeder
{
    public function run()
    {
        Receptionist::create([
            'full_name' => 'Omar bennani', // ✅ Changé de 'name' à 'full_name'
            'email' => 'omar@asio.com',
            'phone' => '0708335842',
            'role' => 'Réceptionniste',
            'password' => Hash::make('password123'),
        ]);

        Receptionist::create([
            'full_name' => 'Pierre Martin', // ✅ Changé de 'name' à 'full_name'
            'email' => 'pierre@clinique.com',
            'phone' => '0987654321',
            'role' => 'Réceptionniste Chef',
            'password' => Hash::make('password123'),
        ]);
    }
}