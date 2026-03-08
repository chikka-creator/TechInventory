<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Buat Akun Admin (Guru Lab)
        \App\Models\User::create([
            'name' => 'Admin Laboratorium',
            'email' => 'admin@sekolah.com',
            'role' => 'admin',
            'password' => Hash::make('12345678'),
        ]);

        // Beri sedikit modal barang di gudang
        \App\Models\Item::create(['name' => 'ESP32 WROOM', 'category' => 'Mikrokontroler', 'stock' => 5]);
        \App\Models\Item::create(['name' => 'Sensor Ultrasonik HC-SR04', 'category' => 'Sensor', 'stock' => 10]);
    }
}