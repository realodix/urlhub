<?php

use Illuminate\Database\Seeder;

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
            'password'   => bcrypt('admin'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'name'       => 'user',
            'email'      => 'user@urlhub.test',
            'password'   => bcrypt('user'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
