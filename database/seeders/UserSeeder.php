<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'       => 'Brayan Serrano',
            'email'      => 'serranobrayan@gmail.com',
            'password'   => Hash::make('uxqH0_341'),
        ])->assignRole('admin');

        User::factory()->create([
            'name'       => 'Aracelly Acuña',
            'email'      => 'aacuna@lamarka.pe',
            'password'   => Hash::make('Titkufvmtm-21'),
        ]);
    }
}
