<?php

namespace Tests\Browser\Components;

use App\Models\Url;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Sometimes while changing some javascript files, the datatables fail to
 * render the table. This test is written to make sure the datatables run
 * as expected.
 */
class DataTablesTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1));
        });
    }

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

    public function testAllUrls()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('dashboard.allurl')
                    ->waitUntilMissingText('Processing')
                    ->assertSee('dashboard');
        });
    }

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
