<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
            'email'      => 'admin@newt.test',
            'password'   => bcrypt('admin'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'name'       => 'user',
            'email'      => 'user@newt.test',
            'password'   => bcrypt('user'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
