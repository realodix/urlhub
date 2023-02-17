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
            'name'       => 'admin',
            'email'      => 'admin@urlhub.test',
            'password'   => Hash::make('admin'),
        ])->assignRole('admin');

        User::factory()->create([
            'name'       => 'user',
            'email'      => 'user@urlhub.test',
            'password'   => Hash::make('user'),
        ]);
    }
}
