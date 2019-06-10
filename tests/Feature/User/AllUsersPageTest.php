<?php

namespace Tests\Feature\User;

use App\Url;
use Tests\TestCase;

class AllUsersPageTest extends TestCase
{
    /** @test */
    public function duplicate()
    {
        $long_url = 'https://laravel.com';

        factory(Url::class)->create([
            'user_id' => null,
            'long_url' => $long_url,
        ]);

        $url = Url::whereLongUrl($long_url)->first();

        $this->loginAsAdmin();

        $response = $this->from(route('dashboard.allurl'))
                         ->get(route('dashboard.duplicate', $url->url_key));
        $response->assertRedirect(route('dashboard.allurl'));

        $count = Url::where('long_url', '=', $long_url)->count();
        $this->assertSame(2, $count);
    }
}
