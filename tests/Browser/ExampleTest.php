<?php

namespace Tests\Browser;

use App\Models\Url;
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
        Url::factory(2)->create();

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
