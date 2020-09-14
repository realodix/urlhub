<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        DB::table('users')->insert([
            'name'       => 'admin',
            'email'      => 'admin@urlhub.test',
            'password'   => Hash::make('admin'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'name'       => 'user',
            'email'      => 'user@urlhub.test',
            'password'   => Hash::make('user'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
