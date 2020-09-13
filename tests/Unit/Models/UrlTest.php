<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\Visit;
use App\Services\UrlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->urlSrvc = new UrlService;

        Url::factory()->create([
            'user_id' => $this->admin()->id,
            'clicks'  => 10,
        ]);

        Url::factory(2)->create([
            'user_id' => null,
            'clicks'  => 10,
        ]);

        config(['urlhub.hash_char' => 'abc']);
    }

    /**
     * @test
     * @group u-model
     */
    public function belongs_to_user()
    {
        $url = Url::factory()->create([
            'user_id' => $this->admin()->id,
        ]);

        $this->assertTrue($url->user()->exists());
    }

    /**
     * @test
     * @group u-model
     */
    public function default_guest_name()
    {
        $url = Url::factory()->create([
            'user_id' => null,
        ]);

        $this->assertSame('Guest', $url->user->name);
    }

    /**
     * @test
     * @group u-model
     */
    public function has_many_url_stat()
    {
        $url = Url::factory()->create();

        Visit::factory()->create([
            'url_id' => $url->id,
        ]);

        $this->assertTrue($url->visit()->exists());
    }

    /**
     * The default guest id must be null.
     *
     * @test
     * @group u-model
     */
    public function default_guest_id()
    {
        $longUrl = 'https://example.com';

        $this->post(route('createshortlink'), [
            'long_url' => $longUrl,
        ]);

        $url = Url::whereLongUrl($longUrl)->first();

        $this->assertSame(null, $url->user_id);
    }

    /**
     * @test
     * @group u-model
     */
    public function setUserIdAttribute_must_be_null()
    {
        $url = Url::factory()->create([
            'user_id' => 0,
        ]);

        $this->assertEquals(null, $url->user_id);
    }

    /**
     * @test
     * @group u-model
     */
    public function setLongUrlAttribute()
    {
        $url = Url::factory()->create([
            'long_url' => 'http://example.com/',
        ]);

        $this->assertSame(
            $url->long_url,
            'http://example.com'
        );
    }

    /**
     * @test
     * @group u-model
     */
    public function getShortUrlAttribute()
    {
        $url = Url::whereUserId($this->admin()->id)->first();

        $this->assertSame(
            $url->short_url,
            url('/'.$url->keyword)
        );
    }

    /**
     * @test
     * @group u-model
     */
    public function total_short_url()
    {
        $this->assertSame(
            3,
            $this->urlSrvc->shortUrlCount()
        );
    }

    /**
     * @test
     * @group u-model
     */
    public function total_short_url_by_me()
    {
        $this->assertSame(
            1,
            $this->urlSrvc->shortUrlCountOwnedBy($this->admin()->id)
        );
    }

    /**
     * @test
     * @group u-model
     */
    public function total_short_url_by_guest()
    {
        $this->assertSame(
            2,
            $this->urlSrvc->shortUrlCountOwnedBy()
        );
    }

    /**
     * @test
     * @group u-model
     */
    public function total_clicks()
    {
        $this->assertSame(
            30,
            $this->urlSrvc->clickCount()
        );
    }

    /**
     * @test
     * @group u-model
     */
    public function total_clicks_by_me()
    {
        $this->assertSame(
            10,
            $this->urlSrvc->clickCountOwnedBy($this->admin()->id)
        );
    }

    /**
     * The number of guests is calculated based on a unique IP.
     *
     * @test
     * @group u-model
     */
    public function total_clicks_by_guest()
    {
        $this->assertSame(
            20,
            $this->urlSrvc->clickCountOwnedBy()
        );
    }
}
