<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\Visit;
use Mockery;
use Tests\TestCase;

class UrlTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url;

        $this->urlWithUserId = 1;
        $this->urlWithoutUserId = 2;
        $this->totalUrl = $this->urlWithUserId + $this->urlWithoutUserId;

        $cwui = 10;
        $cwoui = 10;
        $this->clickWithUserId = $cwui * $this->urlWithUserId;
        $this->clickWithoutUserId = $cwoui * $this->urlWithoutUserId;
        $this->totalClick = $this->clickWithUserId + $this->clickWithoutUserId;

        Url::factory($this->urlWithUserId)->create([
            'user_id' => $this->admin()->id,
            'clicks'  => $cwui,
        ]);

        Url::factory($this->urlWithoutUserId)->create([
            'user_id' => null,
            'clicks'  => $cwoui,
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
    public function setMetaTitleAttributeWhenWebTitleSetToFalse()
    {
        config()->set('urlhub.web_title', false);

        $url = Url::factory()->create([
            'long_url' => 'http://example.com/',
        ]);

        $this->assertSame('No Title', $url->meta_title);
    }

    /**
     * @test
     * @group u-model
     */
    public function urlKey()
    {
        config(['urlhub.hash_length' => 6]);

        $actual = 'https://github.com/realodix/urlhub';
        $expected = 'urlhub';
        $this->assertSame($expected, $this->url->urlKey($actual));
    }

    /**
     * @test
     * @group u-model
     */
    public function keyUsed()
    {
        config(['urlhub.hash_length' => (int) uHub('hash_length') + 1]);

        Url::factory()->create([
            'keyword' => $this->url->randomString(),
        ]);
        $this->assertSame(1, $this->url->keyUsed());

        Url::factory()->create([
            'keyword'   => str_repeat('a', uHub('hash_length')),
            'is_custom' => 1,
        ]);
        $this->assertSame(2, $this->url->keyUsed());

        Url::factory()->create([
            'keyword'   => str_repeat('b', uHub('hash_length') + 2),
            'is_custom' => 1,
        ]);
        $this->assertSame(2, $this->url->keyUsed());

        config(['urlhub.hash_length' => (int) uHub('hash_length') + 3]);
        $this->assertSame(0, $this->url->keyUsed());
        $this->assertSame($this->totalUrl + 3, $this->url->totalUrl());
    }

    /**
     * @test
     * @group u-model
     */
    public function keyUsed2()
    {
        config(['urlhub.hash_length' => 3]);

        config(['urlhub.hash_char' => 'foo']);
        Url::factory()->create([
            'keyword'   => 'foo',
            'is_custom' => 1,
        ]);
        $this->assertSame(1, $this->url->keyUsed());

        config(['urlhub.hash_char' => 'bar']);
        Url::factory()->create([
            'keyword'   => 'bar',
            'is_custom' => 1,
        ]);
        $this->assertSame(1, $this->url->keyUsed());

        config(['urlhub.hash_char' => 'foobar']);
        $this->assertSame(2, $this->url->keyUsed());

        config(['urlhub.hash_char' => 'fooBar']);
        $this->assertSame(1, $this->url->keyUsed());

        config(['urlhub.hash_char' => 'FooBar']);
        $this->assertSame(0, $this->url->keyUsed());
    }

    /**
     * @test
     * @group u-model
     */
    public function keyCapacity()
    {
        $hashLength = uHub('hash_length');
        $hashCharLength = strlen(uHub('hash_char'));
        $keyCapacity = pow($hashCharLength, $hashLength);

        $this->assertSame($keyCapacity, $this->url->keyCapacity());
    }

    /**
     * @test
     * @group u-model
     * @dataProvider keyRemainingProvider
     *
     * @param  mixed  $kc
     * @param  mixed  $nouk
     * @param  mixed  $expected
     */
    public function keyRemaining($kc, $nouk, $expected)
    {
        $mock = Mockery::mock(Url::class)->makePartial();
        $mock->shouldReceive([
            'keyCapacity' => $kc,
            'keyUsed'     => $nouk,
        ]);
        $actual = $mock->keyRemaining();

        $this->assertSame($expected, $actual);
    }

    public function keyRemainingProvider()
    {
        // keyCapacity(), keyUsed(), expected_result
        return [
            [1, 2, 0],
            [3, 2, 1],
        ];
    }

    /**
     * @test
     * @group u-model
     * @dataProvider keyRemainingInPercentProvider
     *
     * @param  mixed  $kc
     * @param  mixed  $nouk
     * @param  mixed  $expected
     */
    public function keyRemainingInPercent($kc, $nouk, $expected)
    {
        $mock = Mockery::mock(Url::class)->makePartial();
        $mock->shouldReceive([
            'keyCapacity' => $kc,
            'keyUsed'     => $nouk,
        ]);
        $actual = $mock->keyRemainingInPercent();

        $this->assertSame($expected, $actual);
    }

    public function keyRemainingInPercentProvider()
    {
        // keyCapacity(), keyUsed(), expected_result
        return [
            [10, 10, '0%'],
            [10, 11, '0%'],
            [pow(10, 6), 999991, '0.01%'],
            [pow(10, 6), 50, '99.99%'],
        ];
    }

    /**
     * @test
     * @group u-model
     */
    public function totalShortUrl()
    {
        $this->assertSame(
            $this->totalUrl,
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
            $this->urlWithUserId,
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
            $this->urlWithoutUserId,
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
            $this->totalClick,
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
            $this->clickWithUserId,
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
            $this->clickWithoutUserId,
            $this->url->clickCount()
        );
    }

    /**
     * @group u-model
     */
    public function testAnonymizeIpWhenConfigSettedTrue()
    {
        config()->set('urlhub.anonymize_ip_addr', true);

        $ip = '192.168.1.1';
        $expected = $this->url->anonymizeIp($ip);
        $actual = '192.168.1.0';

        $this->assertSame($expected, $actual);
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
