<?php

namespace Tests\Browser;

// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('Login');
        });
    }

    public function testDashboardAllurl()
    {
        $user = User::factory()->create([
            'email' => 'taylor@laravel.com',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('identity', $user->email)
                    ->type('password', 'admin')
                    ->press('Login')
                    ->visitRoute('dashboard.allurl')
                    ->waitForText('No Title')
                    ->assertSee('No Title');
        });
    }
}
