<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Creamos el usuario 2
        DB::table('users')->insertOrIgnore([
            'id' => 2,
            'name' => 'Cliente Frecuente',
            'email' => 'cliente2@glamur.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Creamos el usuario 3
        DB::table('users')->insertOrIgnore([
            'id' => 3,
            'name' => 'Experto en Perfumes',
            'email' => 'cliente3@glamur.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
