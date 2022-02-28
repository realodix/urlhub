<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\Visit;
use Tests\TestCase;

class UrlTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url;

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
            'user_id' => null,
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
    public function totalShortUrl()
    {
        $this->assertSame(
            3,
            $this->url->totalUrl()
        );
    }

    /**
     * @test
     * @group u-model
     */
    public function totalShortUrlByMe()
    {
        $this->assertSame(
            1,
            $this->url->urlCount($this->admin()->id)
        );
    }

    /**
     * @test
     * @group u-model
     */
    public function totalShortUrlByGuest()
    {
        $this->assertSame(
            2,
            $this->url->urlCount()
        );
    }

    /**
     * @test
     * @group u-model
     */
    public function totalClicks()
    {
        $this->assertSame(
            30,
            $this->url->totalClick()
        );
    }

    /**
     * @test
     * @group u-model
     */
    public function totalClicksByMe()
    {
        $this->assertSame(
            10,
            $this->url->clickCount($this->admin()->id)
        );
    }

    /**
     * The number of guests is calculated based on a unique IP.
     *
     * @test
     * @group u-model
     */
    public function totalClicksByGuest()
    {
        $this->assertSame(
            20,
            $this->url->clickCount()
        );
    }

    /**
     * @group u-model
     */
    public function testAnonymizeIpWhenConfigSettedFalse()
    {
        config()->set('urlhub.anonymize_ip_addr', false);

        $ip = '192.168.1.1';
        $expected = $this->url->anonymizeIp($ip);
        $actual = $ip;

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     * @dataProvider getDomainProvider
     *
     * @param  mixed  $expected
     * @param  mixed  $actutal
     */
    public function getDomain($expected, $actutal)
    {
        $this->assertEquals($expected, $this->url->getDomain($actutal));
    }

    public function getDomainProvider()
    {
        return [
            ['foo.com', 'http://foo.com/foo/bar?name=taylor'],
            ['foo.com', 'https://foo.com/foo/bar?name=taylor'],
            ['foo.com', 'http://www.foo.com/foo/bar?name=taylor'],
            ['foo.com', 'https://www.foo.com/foo/bar?name=taylor'],
            ['bar.foo.com', 'http://bar.foo.com/foo/bar?name=taylor'],
            ['bar.foo.com', 'https://bar.foo.com/foo/bar?name=taylor'],
            ['bar.foo.com', 'http://www.bar.foo.com/foo/bar?name=taylor'],
            ['bar.foo.com', 'https://www.bar.foo.com/foo/bar?name=taylor'],
        ];
    }

    /**
     * @test
     * @group u-model
     */
    public function getWebTitle()
    {
        $longUrl = 'https://github123456789.com';

        $this->assertSame('github123456789.com - No Title', $this->url->getWebTitle($longUrl));
    }
}
