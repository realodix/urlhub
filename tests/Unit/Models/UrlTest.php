<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\Visit;
use Tests\TestCase;

class UrlTest extends TestCase
{
    private const N_URL_WITH_USER_ID = 1;

    private const N_URL_WITHOUT_USER_ID = 2;

    private const CLICKS = 0;

    private Url $url;

    private int $totalUrl;

    private int $tClick;

    private int $tClickWithUserId;

    private int $tClickWithoutUserId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url;
        $this->totalUrl = self::N_URL_WITH_USER_ID + self::N_URL_WITHOUT_USER_ID;

        $this->tClickWithUserId = self::CLICKS * self::N_URL_WITH_USER_ID;
        $this->tClickWithoutUserId = self::CLICKS * self::N_URL_WITHOUT_USER_ID;
        $this->tClick = $this->tClickWithUserId + $this->tClickWithoutUserId;

        Url::factory(self::N_URL_WITH_USER_ID)->create([
            'user_id' => $this->admin()->id,
            'clicks'  => self::CLICKS,
        ]);

        Url::factory(self::N_URL_WITHOUT_USER_ID)->create([
            'user_id' => null,
            'clicks'  => self::CLICKS,
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

        $this->post(route('short_url.create'), [
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

        $expected = $url->long_url;
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
            'long_url' => 'http://example.com/',
        ]);

        $this->assertSame('No Title', $url->meta_title);
    }

    /**
     * String yang dihasilkan dengan memotong string url dari belakang sepanjang
     * panjang karakter yang telah ditentukan.
     *
     * @test
     * @group u-model
     */
    public function urlKey_default_value()
    {
        $length = 3;
        config(['urlhub.hash_length' => $length]);

        $longUrl = 'https://github.com/realodix';
        $urlKey = $this->url->urlKey($longUrl);

        $this->assertSame(substr($longUrl, -$length), $urlKey);
    }

    /**
     * Karena kunci sudah ada, maka generator akan terus diulangi hingga
     * menghasilkan kunci yang unik atau tidak ada yang sama.
     *
     * @test
     * @group u-model
     */
    public function urlKey_generated_string()
    {
        $length = 3;
        config(['urlhub.hash_length' => $length]);

        $longUrl = 'https://github.com/realodix';
        Url::factory()->create([
            'keyword'  => $this->url->urlKey($longUrl),
        ]);

        $this->assertNotSame(substr($longUrl, -$length), $this->url->urlKey($longUrl));
    }

    /**
     * Panjang dari karakter kunci yang dihasilkan harus sama dengan panjang
     * karakter yang telah ditentukan.
     *
     * @test
     * @group u-model
     */
    public function urlKey_specified_hash_length()
    {
        config(['urlhub.hash_length' => 6]);
        $actual = 'https://github.com/realodix';
        $expected = 'alodix';
        $this->assertSame($expected, $this->url->urlKey($actual));

        config(['urlhub.hash_length' => 9]);
        $actual = 'https://github.com/realodix';
        $expected = 'mrealodix';
        $this->assertSame($expected, $this->url->urlKey($actual));

        config(['urlhub.hash_length' => 12]);
        $actual = 'https://github.com/realodix';
        $expected = 'bcomrealodix';
        $this->assertSame($expected, $this->url->urlKey($actual));
    }

    /**
     * Karakter yang dihasilkan harus benar-benar mengikuti karakter yang telah
     * ditentukan.
     *
     * @test
     * @group u-model
     */
    public function urlKey_specified_character()
    {
        $url = 'https://example.com/abc';
        config(['urlhub.hash_length' => 3]);

        $this->assertSame('abc', $this->url->urlKey($url));

        config(['urlhub.hash_char' => 'xyz']);
        $this->assertMatchesRegularExpression('/[xyz]/', $this->url->urlKey($url));
        $this->assertDoesNotMatchRegularExpression('/[abc]/', $this->url->urlKey($url));

        config(['urlhub.hash_length' => 4]);
        config(['urlhub.hash_char' => 'abcm']);
        $this->assertSame('mabc', $this->url->urlKey($url));
    }

    /**
     * String yang dihasilkan tidak boleh sama dengan string yang telah ada di
     * config('urlhub.reserved_keyword')
     *
     * @test
     * @group u-model
     */
    public function urlKey_prevent_reserved_keyword()
    {
        $actual = 'https://example.com/css';
        $expected = 'css';

        config(['urlhub.reserved_keyword' => [$expected]]);
        config(['urlhub.hash_length' => strlen($expected)]);

        $this->assertNotSame($expected, $this->url->urlKey($actual));
    }

    /**
     * String yang dihasilkan tidak boleh sama dengan string yang telah ada di
     * registered route path. Di sini, string yang dihasilkan sebagai keyword
     * adalah 'admin', dimana 'admin' sudah digunakan sebagai route path.
     *
     * @test
     * @group u-model
     */
    public function urlKey_prevent_generating_strings_that_are_in_registered_route_path()
    {
        $actual = 'https://example.com/admin';
        $expected = 'admin';

        config(['urlhub.hash_length' => strlen($expected)]);

        $this->assertNotSame($expected, $this->url->urlKey($actual));
    }

    /**
     * Pengujian dilakukan berdasarkan panjang karakternya.
     *
     * @test
     * @group u-model
     */
    public function keyUsed()
    {
        config(['urlhub.hash_length' => config('urlhub.hash_length') + 1]);

        Url::factory()->create([
            'keyword' => $this->url->randomString(),
        ]);
        $this->assertSame(1, $this->url->keyUsed());

        Url::factory()->create([
            'keyword'   => str_repeat('a', config('urlhub.hash_length')),
            'is_custom' => true,
        ]);
        $this->assertSame(2, $this->url->keyUsed());

        // Karena panjang karakter 'keyword' berbeda dengan dengan 'urlhub.hash_length',
        // maka ini tidak ikut terhitung.
        Url::factory()->create([
            'keyword'   => str_repeat('b', config('urlhub.hash_length') + 2),
            'is_custom' => true,
        ]);
        $this->assertSame(2, $this->url->keyUsed());

        config(['urlhub.hash_length' => config('urlhub.hash_length') + 3]);
        $this->assertSame(0, $this->url->keyUsed());
        $this->assertSame($this->totalUrl + 3, $this->url->totalUrl());
    }

    /**
     * Pengujian dilakukan berdasarkan karakter yang telah ditetapkan pada
     * 'urlhub.hash_char'. Jika salah satu karakter 'keyword' tidak ada di
     * 'urlhub.hash_char', maka seharusnya ini tidak dapat dihitung.
     *
     * @test
     * @group u-model
     */
    public function keyUsed2()
    {
        config(['urlhub.hash_length' => 3]);

        config(['urlhub.hash_char' => 'foo']);
        Url::factory()->create([
            'keyword'   => 'foo',
            'is_custom' => true,
        ]);
        $this->assertSame(1, $this->url->keyUsed());

        config(['urlhub.hash_char' => 'bar']);
        Url::factory()->create([
            'keyword'   => 'bar',
            'is_custom' => true,
        ]);
        $this->assertSame(1, $this->url->keyUsed());

        // Sudah ada 2 URL yang dibuat dengan keyword 'foo' dan 'bar', maka
        // seharusnya ada 2 saja.
        config(['urlhub.hash_char' => 'foobar']);
        $this->assertSame(2, $this->url->keyUsed());

        // Sudah ada 2 URL yang dibuat dengan keyword 'foo' dan 'bar', maka
        // seharusnya ada 1 saja karena 'bar' tidak bisa terhitung.
        config(['urlhub.hash_char' => 'fooBar']);
        $this->assertSame(1, $this->url->keyUsed());

        // Sudah ada 2 URL yang dibuat dengan keyword 'foo' dan 'bar', maka
        // seharusnya tidak ada sama sekali karena 'foo' dan 'bar' tidak
        // bisa terhitung.
        config(['urlhub.hash_char' => 'FooBar']);
        $this->assertSame(0, $this->url->keyUsed());
    }

    /**
     * @test
     * @group u-model
     */
    public function keyCapacity()
    {
        $hashLength = config('urlhub.hash_length');
        $hashCharLength = strlen(config('urlhub.hash_char'));
        $keyCapacity = pow($hashCharLength, $hashLength);

        $this->assertSame($keyCapacity, $this->url->keyCapacity());
    }

    /**
     * @test
     * @group u-model
     * @dataProvider keyRemainingProvider
     *
     * @param mixed $kc
     * @param mixed $nouk
     * @param mixed $expected
     */
    public function keyRemaining($kc, $nouk, $expected)
    {
        $mock = \Mockery::mock(Url::class)->makePartial();
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
     * @param mixed $kc
     * @param mixed $nouk
     * @param mixed $expected
     */
    public function keyRemainingInPercent($kc, $nouk, $expected)
    {
        $mock = \Mockery::mock(Url::class)->makePartial();
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
            [pow(10, 6), 0, '100%'],
        ];
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
        $actual = $this->url->urlCount($this->admin()->id);

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function totalShortUrlByGuest()
    {
        $expected = self::N_URL_WITHOUT_USER_ID;
        $actual = $this->url->urlCount();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function totalClicks()
    {
        $expected = $this->tClick;
        $actual = $this->url->totalClick();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function totalClicksByMe()
    {
        $expected = $this->tClickWithUserId;
        $actual = $this->url->clickCount($this->admin()->id);

        $this->assertSame($expected, $actual);
    }

    /**
     * The number of guests is calculated based on a unique IP.
     *
     * @test
     * @group u-model
     */
    public function totalClicksByGuest()
    {
        $expected = $this->tClickWithoutUserId;
        $actual = $this->url->clickCount();

        $this->assertSame($expected, $actual);
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
     * @param mixed $expected
     * @param mixed $actutal
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
        $expected = 'github123456789.com - Untitled';
        $actual = $this->url->getWebTitle('https://github123456789.com');

        $this->assertSame($expected, $actual);
    }
}
