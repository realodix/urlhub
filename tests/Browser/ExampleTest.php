<?php

namespace Tests\Browser;

use App\Models\Url;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        Url::factory()->create();
        User::factory()->create();
    }

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
