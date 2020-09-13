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
        UserSeeder::factory()->make();
        RolesAndPermissionsSeeder::factory()->make();

        // Multiple with factory
        // App\Models\User::factory(200)->create();
        // App\Models\Url::factory(100000)->create();
    }
}
