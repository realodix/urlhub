<?php

namespace Database\Seeders;

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
        $this->call(UserSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);

        // Multiple with factory
        // User::factory(200)->create();
        // Url::factory(100000)->create();
    }
}
