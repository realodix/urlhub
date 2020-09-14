<?php

namespace Tests\Browser;

use App\Models\Url;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DataTablesTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1));
        });
    }

    /**
     * Dashboard Page.
     *
     * @return void
     */
    public function testDashboard()
    {
        $text = 'dashboard';
        $user = User::find(1);

        Url::factory()->create([
            'user_id'    => $user->id,
            'meta_title' => $text,
        ]);

        $this->browse(function (Browser $browser) use ($text) {
            $browser->visitRoute('dashboard.allurl')
                    ->waitForText($text)
                    ->assertSee($text);
        });
    }

    /**
     * AllUrls Page.
     *
     * @return void
     */
    public function testAllUrls()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('dashboard.allurl')
                    ->waitUntilMissingText('Processing')
                    ->assertSee('dashboard');
        });
    }

    /**
     * All Users Page.
     *
     * @return void
     */
    public function testAllUsers()
    {
        $user = User::factory()->create([
            'email' => 'laravel@example.com',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visitRoute('user.index')
                    ->waitUntilMissingText('Processing')
                    ->assertSee($user->email);
        });
    }
}
