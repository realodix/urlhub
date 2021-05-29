<?php

namespace Tests\Unit\Controllers;

use App\Models\Url;
use App\Rules\StrAlphaUnderscore;
use App\Rules\StrLowercase;
use App\Rules\URL\KeywordBlacklist;
use App\Services\KeyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    /**
     * @var \App\Services\KeyService
     */
    protected $keySrvc;

    protected function setUp(): void
    {
        parent::setUp();

        $this->keySrvc = new KeyService();
    }

    /**
     * When the guest shortens the URL, the user_id column in the Url table must be null.
     *
     * @test
     * @group u-controller
     */
    public function guestDoesUrlShortening()
    {
        $longUrl = 'https://laravel.com';

        $this->post(route('createshortlink'), [
            'long_url' => $longUrl,
        ]);

        $url = Url::whereLongUrl($longUrl)->first();

        $this->assertSame(null, $url->user_id);
    }

    /**
     * When the User shortens the URL, the user_id column in the Url table must be filled
     * with the authenticated user id.
     *
     * @test
     * @group u-controller
     */
    public function userDoesUrlShortening()
    {
        $user = $this->admin();
        $longUrl = 'https://laravel.com';

        $this->loginAsAdmin();
        $this->post(route('createshortlink'), ['long_url' => $longUrl,]);
        $url = Url::whereLongUrl($longUrl)->first();

        $this->assertSame($user->id, $url->user_id);
    }

    /**
     * Test generates url key.
     * Step 1: The first thing it does is create 'keywords' by directly retrieving them
     * from the URL entered.
     * Step 2: If the keywords generated by the Step 1 are already in the database, then
     * UrlHub must generate random keywords.
     *
     * @test
     * @group u-controller
     */
    public function keywordCollumn()
    {
        config(['urlhub.hash_length' => 3]);

        $longUrl = 'https://github.com/realodix';
        $expectedKey = $this->keySrvc->urlKey($longUrl);
        $this->post(route('createshortlink'), ['long_url' => $longUrl]);
        $url = Url::whereLongUrl($longUrl)->first();
        $this->assertSame($expectedKey, $url->keyword);

        $longUrl = 'https://github.com/r3alodix';
        $expectedKey = $this->keySrvc->urlKey($longUrl);
        $this->post(route('createshortlink'), ['long_url' => $longUrl]);
        $url = Url::whereLongUrl($longUrl)->first();
        $this->assertNotSame($expectedKey, $url->keyword);
    }

    /**
     * Users shorten the URLs, they don't fill in the custom keyword field. The is_custom
     * column must be filled with 0 / false.
     *
     * @test
     * @group u-controller
     */
    public function shortenUrlWithNonCustomKeyword()
    {
        $longUrl = 'https://laravel.com';

        $this->post(route('createshortlink'), ['long_url' => $longUrl,]);
        $url = Url::whereLongUrl($longUrl)->first();

        $this->assertFalse($url->is_custom);
    }

    /**
     * The user shortens the URL and they fill in the custom keyword field. The keyword
     * column in the URL table must be filled with the keywords requested by the user
     * and the is_custom column must be filled with 1 / true.
     *
     * @test
     * @group u-controller
     */
    public function shortenUrlWithCustomKeyword()
    {
        config(['urlhub.hash_length' => 6]);

        $longUrl = 'https://laravel.com';
        $customKey = 'foo_bar';

        $this->post(route('createshortlink'), [
            'long_url'   => $longUrl,
            'custom_key' => $customKey,
        ]);
        $url = Url::whereLongUrl($longUrl)->first();

        $this->assertSame($customKey, $url->keyword);
        $this->assertTrue($url->is_custom);
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
     */
    public function customKeyValidationFail($data)
    {
        Url::factory()->create([
            'keyword' => 'laravel',
        ]);

        $request = new Request;

        $v = Validator::make($request->all(), [
            'keyword' => [
                'max:20',
                'unique:urls',
                new StrAlphaUnderscore,
                new StrLowercase,
                new KeywordBlacklist,
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
