<?php

namespace Tests\Browser;

// use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Url;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create();
        Url::factory()->create();
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
