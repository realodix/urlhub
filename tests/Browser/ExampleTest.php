<?php

namespace Tests\Browser;

// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
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
        \App\Models\User::factory()->create();
        \App\Models\Url::factory()->create();

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('identity', 'admin')
                    ->type('password', 'admin')
                    ->press('Login')
                    ->visitRoute('dashboard.allurl')
                    ->waitForText('No Title')
                    ->assertSee('No Title');
        });
    }
}
