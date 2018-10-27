<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);

        // Multiple with factory
        // factory(App\User::class, 200)->create();
        // factory(App\Url::class, 100000)->create();
    }
}
