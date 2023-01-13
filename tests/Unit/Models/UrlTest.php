<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\Visit;
use Tests\TestCase;

class UrlTest extends TestCase
{
    private const N_URL_WITH_USER_ID = 1;

    private const N_URL_WITHOUT_USER_ID = 2;

    private Url $url;

    private int $totalUrl;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url;

        $this->totalUrl = self::N_URL_WITH_USER_ID + self::N_URL_WITHOUT_USER_ID;

        Url::factory(self::N_URL_WITH_USER_ID)->create([
            'user_id' => $this->admin()->id,
        ]);

        Url::factory(self::N_URL_WITHOUT_USER_ID)->create([
            'user_id' => Url::GUEST_ID,
        ]);
    }

    /**
     * @test
     * @group u-model
     */
    public function belongsToUser()
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
    public function defaultGuestName()
    {
        $url = Url::factory()->create([
            'user_id' => Url::GUEST_ID,
        ]);

        $this->assertSame('Guest', $url->user->name);
    }

    /**
     * @test
     * @group u-model
     */
    public function hasManyUrlStat()
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
    public function defaultGuestId()
    {
        $longUrl = 'https://example.com';

        $this->post(route('su_create'), [
            'long_url' => $longUrl,
        ]);

        $url = Url::whereDestination($longUrl)->first();

        $this->assertSame(null, $url->user_id);
    }

    /**
     * @test
     * @group u-model
     */
    public function setUserIdAttributeMustBeNull()
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
            'destination' => 'http://example.com/',
        ]);

        $expected = $url->destination;
        $actual = 'http://example.com';
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function getShortUrlAttribute()
    {
        $url = Url::whereUserId($this->admin()->id)->first();

        $expected = $url->short_url;
        $actual = url('/'.$url->keyword);
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function setMetaTitleAttributeWhenWebTitleSetToFalse()
    {
        config()->set('urlhub.web_title', false);

        $url = Url::factory()->create([
            'destination' => 'http://example.com/',
        ]);

        $this->assertSame('No Title', $url->title);
    }

    /**
     * @test
     * @group u-model
     */
    public function totalShortUrl()
    {
        $expected = $this->totalUrl;
        $actual = $this->url->totalUrl();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function totalShortUrlByMe()
    {
        $expected = self::N_URL_WITH_USER_ID;
        $actual = $this->url->numberOfUrls($this->admin()->id);

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function totalShortUrlByGuest()
    {
        $expected = self::N_URL_WITHOUT_USER_ID;
        $actual = $this->url->numberOfUrlsByGuests();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function totalClicks()
    {
        Visit::factory()->create();

        $url = new Url;

        $expected = 1;
        $actual = $url->totalClick();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function numberOfClicks()
    {
        Visit::factory()->create([
            'url_id' => 1,
            'is_first_click' => true,
        ]);

        Visit::factory()->create([
            'url_id' => 1,
            'is_first_click' => false,
        ]);

        $expected = 2;
        $actual = $this->url->numberOfClicks(1);

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function numberOfClicksAndUnique()
    {
        Visit::factory()->create([
            'url_id' => 1,
            'is_first_click' => true,
        ]);

        Visit::factory()->create([
            'url_id' => 1,
            'is_first_click' => false,
        ]);

        $expected = 1;
        $actual = $this->url->numberOfClicks(1, unique: true);

        $this->assertSame($expected, $actual);
    }

    /**
     * Total klik dari setiap shortened URLs yang dibuat oleh user tertentu
     *
     * @test
     * @group u-model
     */
    public function numberOfClicksPerUser()
    {
        $userId = $this->admin()->id;
        $url = Url::factory()->create([
            'user_id' => $userId,
        ]);
        Visit::factory()->create([
            'url_id' => $url->id,
        ]);

        $expected = Visit::whereUrlId($url->id)->count();
        $actual = $this->url->numberOfClicksPerUser(userId: $url->user_id);

        $this->assertSame($userId, $url->user_id);
        $this->assertSame($expected, $actual);
    }

    /**
     * Total klik dari setiap shortened URLs yang dibuat oleh guest user
     *
     * @test
     * @group u-model
     */
    public function numberOfClicksFromGuests()
    {
        $userId = null;
        $url = Url::factory()->create([
            'user_id' => $userId,
        ]);
        Visit::factory()->create([
            'url_id' => $url->id,
        ]);

        $expected = Visit::whereUrlId($url->id)->count();
        $actual = $this->url->numberOfClicksFromGuests();

        $this->assertSame($userId, $url->user_id);
        $this->assertSame($expected, $actual);
    }
}
