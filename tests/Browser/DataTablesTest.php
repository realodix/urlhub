<?php

namespace Tests\Browser;

use App\Models\Url;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DataTablesTest extends DuskTestCase
{
    /**
     * Dashboard Page
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
            $browser->loginAs(User::find(1))
                    ->visitRoute('dashboard.allurl')
                    ->waitForText('Processing')
                    ->waitForText($text)
                    ->assertSee($text);
        });
    }

    /**
     * AllUrls Page
     *
     * @return void
     */
    public function testAllUrls()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visitRoute('dashboard.allurl')
                    ->waitForText('Processing')
                    ->waitForText('No Title')
                    ->assertSee('No Title');
        });
    }

    /**
     * All Users Page
     *
     * @return void
     */
    public function testAllUsers()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visitRoute('user.index')
                    ->waitForText('Processing')
                    ->waitForText('@example')
                    ->assertSee('@example');
        });
    }
}
