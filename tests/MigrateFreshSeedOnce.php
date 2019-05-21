<?php

namespace Tests;

use App\User;
use Illuminate\Support\Facades\Artisan;

trait MigrateFreshSeedOnce
{
    /**
    * If true, setup has run at least once.
    *
    * @var boolean
    */
    protected static $setUpHasRunOnce = false;

    /**
    * After the first run of setUp "migrate:fresh --seed"
    *
    * @return void
    */
    public function setUp():void
    {
        parent::setUp();

        if (!static::$setUpHasRunOnce) {
            Artisan::call('migrate:fresh');
            Artisan::call(
                'db:seed', ['--class' => 'DatabaseSeeder']
            );

            static::$setUpHasRunOnce = true;
         }
    }

    protected function loginAsAdmin()
    {
        $admin = User::whereName('admin')->first();

        $this->actingAs($admin);

        return $admin;
    }

        protected function loginAsUser()
    {
        $user = User::whereName('user')->first();

        $this->actingAs($user);

        return $user;
    }

    protected function adminPassword()
    {
        return 'admin';
    }

    protected function userPassword()
    {
        return 'user';
    }
}
