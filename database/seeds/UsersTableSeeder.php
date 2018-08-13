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
            'name'           => 'admin',
            'email'          => 'admin@admin.com',
            'password'       => bcrypt('admin'),
            'remember_token' => '1EuDF59xgwO4YZ7Vau0KkeAx1p0k7GkkGuOl6o0xhtwa24HX0PBq3yClOBd6',
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);
    }
}
