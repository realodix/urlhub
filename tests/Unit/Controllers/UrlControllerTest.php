<?php

namespace Tests\Unit\Controllers;

use App\Models\Url;
use App\Rules\StrAlphaUnderscore;
use App\Rules\StrLowercase;
use App\Rules\Url\KeywordBlacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
    public function customKeyValidationPass()
    {
        $response = $this->post(route('home').'/validate-custom-key', [
            'keyword' => 'hello',
        ]);

        $response->assertJson(['success' => 'Available']);
    }

    /**
     * @test
     * @group u-controller
     * @dataProvider customKeyValidationFailProvider
     *
     * @param mixed $data
     */
    public function customKeyValidationFail($data)
    {
        Url::factory()->create([
            'keyword' => 'laravel',
        ]);

        $request = new Request();

        $v = Validator::make($request->all(), [
            'keyword' => [
                'max:20',
                'unique:urls',
                new StrAlphaUnderscore(),
                new StrLowercase(),
                new KeywordBlacklist(),
            ],
        ]);

        $response = $this->post(route('home').'/validate-custom-key', [
            'keyword' => $data,
        ]);

        $response->assertJson(['errors' => $v->errors()->all()]);
    }

    public function customKeyValidationFailProvider()
    {
        return [
            [str_repeat('a', 50)],
            ['FOOBAR'],
            ['foo-bar'],
            ['foo~bar'],
            ['login'],
        ];
    }
}
