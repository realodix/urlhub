<?php

namespace Tests\Feature;

use App\Models\Url;
use Tests\TestCase;

class ShortenUrlWithLongUrlAlreadyExistTest extends TestCase
{
    /**
     * Guest A and guest B.
     *
     * @test
     */
    public function longUrlAlreadyExist()
    {
        $url = Url::factory()->create([
            'user_id' => null,
        ]);

        $response = $this->post(route('su_create'), [
            'long_url' => $url->destination,
        ]);

        $response
            ->assertRedirectToRoute('su_detail', $url->keyword)
            ->assertSessionHas('msgLinkAlreadyExists');

        $this->assertCount(1, Url::all());
    }

    /**
     * Memastikan long URL dengan atau tanpa trailing slashes adalah sama.
     *
     * @test
     */
    public function ensuresLongUrlsWithOrWithoutSlashesAreTheSameUrl()
    {
        $longUrl_1 = 'https://example.com/';
        $longUrl_2 = 'https://example.com';

        $url = Url::factory()->create([
            'user_id'     => null,
            'destination' => $longUrl_1,
        ]);

        $response = $this->post(route('su_create'), [
            'long_url' => $longUrl_2,
        ]);

        $response
            ->assertRedirectToRoute('su_detail', $url->keyword)
            ->assertSessionHas('msgLinkAlreadyExists');

        $this->assertCount(1, Url::all());
    }

    /**
     * Authen user A and authen user A.
     *
     * @test
     */
    public function longUrlAlreadyExist2()
    {
        $user = $this->admin();

        $url = Url::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($this->admin())
            ->post(route('su_create'), [
                'long_url' => $url->destination,
            ]);

        $response
            ->assertRedirectToRoute('su_detail', $url->keyword)
            ->assertSessionHas('msgLinkAlreadyExists');

        $this->assertCount(1, Url::all());
    }

    /**
     * Guest and authen user.
     *
     * @test
     */
    public function longUrlAlreadyExistsButStillAccepted()
    {
        $url = Url::factory()->create();

        $response = $this->post(route('su_create'), [
            'long_url' => $url->destination,
        ]);

        $url = Url::whereUserId(null)->first();

        $response->assertRedirectToRoute('su_detail', $url->keyword);
        $this->assertCount(2, Url::all());
    }

    /**
     * Authen user A and authen user B.
     *
     * @test
     */
    public function longUrlAlreadyExistsButStillAccepted2()
    {
        $user = $this->admin();
        $user2 = $this->nonAdmin();

        $url = Url::factory()->create([
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($this->admin())
            ->post(route('su_create'), [
                'long_url' => $url->destination,
            ]);

        $url = Url::whereUserId($user->id)->first();

        $response->assertRedirectToRoute('su_detail', $url->keyword);
        $this->assertCount(2, Url::all());
    }

    /**
     * Authen user and guest.
     *
     * @test
     */
    public function longUrlAlreadyExistsButStillAccepted3()
    {
        $user = $this->admin();

        $url = Url::factory()->create([
            'user_id' => null,
        ]);

        $response = $this->actingAs($this->admin())
            ->post(route('su_create'), [
                'long_url' => $url->destination,
            ]);

        $url = Url::whereUserId($user->id)->first();

        $response->assertRedirectToRoute('su_detail', $url->keyword);
        $this->assertCount(2, Url::all());
    }

    /** @test */
    public function createShortUrlWithWrongUrlFormat()
    {
        $response = $this->post(route('su_create'), [
            'long_url' => 'wrong-url-format',
        ]);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHasErrors('long_url');
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Short URLs
    |--------------------------------------------------------------------------
    |
    | Short URL with custom keyword and long url already in database.
    */

    /** @test */
    public function cstLongUrlAlreadyExist()
    {
        $url = Url::factory()->create([
            'user_id' => null,
        ]);

        $customKey = 'laravel';

        $response = $this->post(route('su_create'), [
            'long_url'   => $url->destination,
            'custom_key' => $customKey,
        ]);
        $response->assertRedirectToRoute('su_detail', $url->keyword);

        $response2 = $this->get(route('home').'/'.$customKey);
        $response2->assertNotFound();
    }

    /** @test */
    public function cstLongUrlAlreadyExist2()
    {
        $url = Url::factory()->create([
            'user_id' => null,
        ]);

        $customKey = 'laravel';

        $response = $this->actingAs($this->nonAdmin())
            ->post(route('su_create'), [
                'long_url'   => $url->destination,
                'custom_key' => $customKey,
            ]);

        $response->assertRedirectToRoute('su_detail', $customKey);

        $response2 = $this->get(route('home').'/'.$customKey);
        $response2->assertRedirect($url->destination);

        $this->assertCount(2, Url::all());
    }

    /** @test */
    public function cstCstKeywordAlreadyExist()
    {
        $url = Url::factory()->create();

        $response = $this->post(route('su_create'), [
            'long_url'   => 'https://laravel-news.com',
            'custom_key' => $url->keyword,
        ]);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHasErrors('custom_key');

        $this->assertCount(1, Url::all());
    }

    /**
     * With authenticated user.
     *
     * @test
     */
    public function cstCstKeywordAlreadyExist2()
    {
        $url = Url::factory()->create();

        $response = $this->actingAs($this->nonAdmin())
            ->post(route('su_create'), [
                'long_url'   => 'https://laravel-news.com',
                'custom_key' => $url->keyword,
            ]);

        $response
            ->assertRedirectToRoute('home')
            ->assertSessionHasErrors('custom_key');

        $this->assertCount(1, Url::all());
    }
}
