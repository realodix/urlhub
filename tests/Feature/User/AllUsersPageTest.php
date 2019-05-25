<?php

namespace Tests\Feature\User;

use App\Url;
use Tests\TestCase;

class AllUsersPageTest extends TestCase
{
    protected function duplicateGetRoute($value)
    {
        return route('admin.duplicate', $value);
    }

    /** @test */
    public function admin_can_access_all_users_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('user.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function user_cant_access_all_users_page()
    {
        $this->loginAsUser();

        $response = $this->get(route('user.index'));
        $response->assertStatus(403);
    }

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

        $response = $this->from(route('admin.allurl'))
                         ->get($this->duplicateGetRoute($url->url_key));
        $response->assertRedirect(route('admin.allurl'));

        $count = Url::where('long_url', '=', $long_url)->count();
        $this->assertSame(2, $count);
    }
}
