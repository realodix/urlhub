<?php

namespace Tests\Unit\Controllers;

use App\Models\Url;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    /**
     * When the guest (users who are not logged in) shortens the URL, the user_id column
     * (Urls table) must be filled with a null value.
     *
     * @test
     * @group u-controller
     */
    public function guestShortenURL()
    {
        $longUrl = 'https://laravel.com';

        $this->post(route('createshortlink'), [
            'long_url' => $longUrl,
        ]);

        $url = Url::whereLongUrl($longUrl)->first();

        $this->assertSame(null, $url->user_id);
    }

    /**
     * When the User shortens the URL, the user_id column (Urls table) must be filled with
     * the authenticated user id.
     *
     * @test
     * @group u-controller
     */
    public function userShortenURL()
    {
        $user = $this->admin();
        $longUrl = 'https://laravel.com';

        $this->loginAsAdmin();
        $this->post(route('createshortlink'), ['long_url' => $longUrl]);
        $url = Url::whereLongUrl($longUrl)->first();

        $this->assertSame($user->id, $url->user_id);
    }

    /**
     * @test
     * @group u-controller
     */
    public function customKeyValidation()
    {
        $component = \Livewire\Livewire::test(\App\Http\Livewire\UrlCheck::class);
        $component->assertStatus(200)
            ->set('keyword', '!')
            ->assertHasErrors([
                'keyword' => 'alpha_num',
            ])
            ->set('keyword', 'FOO')
            ->assertHasErrors([
                'keyword' => 'lowercase:field',
            ])
            ->set('keyword', 'foo_aa')
            ->assertHasNoErrors([
                'keyword' => new \App\Rules\StrAlphaUnderscore,
            ]);
    }
}
