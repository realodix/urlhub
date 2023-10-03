<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Services\KeyGeneratorService;
use Tests\TestCase;

class KeyGeneratorServiceTest extends TestCase
{
    private Url $url;

    private KeyGeneratorService $keyGenerator;

    private const N_URL_WITH_USER_ID = 1;

    private const N_URL_WITHOUT_USER_ID = 2;

    private int $totalUrl;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url;

        $this->keyGenerator = app(KeyGeneratorService::class);

        $this->totalUrl = self::N_URL_WITH_USER_ID + self::N_URL_WITHOUT_USER_ID;
    }

    /**
     * String yang dihasilkan dengan memotong string url dari belakang sepanjang
     * panjang karakter yang telah ditentukan.
     *
     * @test
     * @group u-model
     */
    public function urlKey_default_value(): void
    {
        $length = 3;
        config(['urlhub.hash_length' => $length]);

        $longUrl = 'https://github.com/realodix';
        $urlKey = $this->keyGenerator->urlKey($longUrl);

        $this->assertSame(substr($longUrl, -$length), $urlKey);
    }

    /**
     * String yang dihasilkan dari URL harus berupa huruf kecil.
     *
     * @test
     * @group u-model
     */
    public function urlKey_default_value_mus_be_lowercase(): void
    {
        $length = 4;
        config(['urlhub.hash_length' => $length]);

        $longUrl = 'https://github.com/realoDIX';
        $urlKey = $this->keyGenerator->urlKey($longUrl);

        $this->assertSame(mb_strtolower(substr($longUrl, -$length)), $urlKey);
        $this->assertNotSame(substr($longUrl, -$length), $urlKey);
    }

    /**
     * Karena kunci sudah ada, maka generator akan terus diulangi hingga
     * menghasilkan kunci yang unik atau tidak ada yang sama.
     *
     * @test
     * @group u-model
     */
    public function urlKey_generated_string(): void
    {
        $length = 3;
        config(['urlhub.hash_length' => $length]);

        $longUrl = 'https://github.com/realodix';
        Url::factory()->create(['keyword'  => $this->keyGenerator->urlKey($longUrl)]);

        $this->assertNotSame(substr($longUrl, -$length), $this->keyGenerator->urlKey($longUrl));
    }

    /**
     * Panjang dari karakter kunci yang dihasilkan harus sama dengan panjang
     * karakter yang telah ditentukan.
     *
     * @test
     * @group u-model
     */
    public function urlKey_specified_hash_length(): void
    {
        config(['urlhub.hash_length' => 6]);
        $actual = 'https://github.com/realodix';
        $expected = 'alodix';
        $this->assertSame($expected, $this->keyGenerator->urlKey($actual));

        config(['urlhub.hash_length' => 9]);
        $actual = 'https://github.com/realodix';
        $expected = 'mrealodix';
        $this->assertSame($expected, $this->keyGenerator->urlKey($actual));

        config(['urlhub.hash_length' => 12]);
        $actual = 'https://github.com/realodix';
        $expected = 'bcomrealodix';
        $this->assertSame($expected, $this->keyGenerator->urlKey($actual));
    }

    /**
     * String yang dihasilkan tidak boleh sama dengan string yang telah ada di
     * config('urlhub.reserved_keyword')
     *
     * @test
     * @group u-model
     */
    public function urlKey_prevent_reserved_keyword(): void
    {
        $actual = 'https://example.com/css';
        $expected = 'css';

        config(['urlhub.reserved_keyword' => [$expected]]);
        config(['urlhub.hash_length' => strlen($expected)]);

        $this->assertNotSame($expected, $this->keyGenerator->urlKey($actual));
    }

    /**
     * String yang dihasilkan tidak boleh sama dengan string yang telah ada di
     * registered route path. Di sini, string yang dihasilkan sebagai keyword
     * adalah 'admin', dimana 'admin' sudah digunakan sebagai route path.
     *
     * @test
     * @group u-model
     */
    public function urlKey_prevent_generating_strings_that_are_in_registered_route_path(): void
    {
        $actual = 'https://example.com/admin';
        $expected = 'admin';

        config(['urlhub.hash_length' => strlen($expected)]);

        $this->assertNotSame($expected, $this->keyGenerator->urlKey($actual));
    }

    /**
     * @test
     * @group u-model
     */
    public function generateSimpleString(): void
    {
        config(['urlhub.hash_length' => 3]);

        $this->assertSame('bar', $this->keyGenerator->generateSimpleString('foobar'));
        $this->assertSame('bar', $this->keyGenerator->generateSimpleString('foob/ar'));
    }

    /**
     * @test
     * @group u-model
     */
    public function assertStringCanBeUsedAsKey(): void
    {
        $this->assertTrue($this->keyGenerator->assertStringCanBeUsedAsKey('foo'));
        $this->assertFalse($this->keyGenerator->assertStringCanBeUsedAsKey('login'));
    }

    /**
     * @test
     * @group u-model
     */
    public function maxCapacity(): void
    {
        $this->assertIsInt($this->keyGenerator->maxCapacity());

        // config(['urlhub.hash_length' => 11]);
        // $this->assertIsFloat($this->keyGenerator->maxCapacity());
    }

    /**
     * Pengujian dilakukan berdasarkan panjang karakternya.
     *
     * @test
     * @group u-model
     */
    public function usedCapacity(): void
    {
        config(['urlhub.hash_length' => config('urlhub.hash_length') + 1]);

        Url::factory()->create([
            'keyword' => $this->keyGenerator->generateRandomString(),
        ]);
        $this->assertSame(1, $this->keyGenerator->usedCapacity());

        Url::factory()->create([
            'keyword'   => str_repeat('a', config('urlhub.hash_length')),
            'is_custom' => true,
        ]);
        $this->assertSame(2, $this->keyGenerator->usedCapacity());

        // Karena panjang karakter 'keyword' berbeda dengan dengan 'urlhub.hash_length',
        // maka ini tidak ikut terhitung.
        Url::factory()->create([
            'keyword'   => str_repeat('b', config('urlhub.hash_length') + 2),
            'is_custom' => true,
        ]);
        $this->assertSame(2, $this->keyGenerator->usedCapacity());

        config(['urlhub.hash_length' => config('urlhub.hash_length') + 3]);
        $this->assertSame(0, $this->keyGenerator->usedCapacity());
        $this->assertSame($this->totalUrl, $this->url->count());
    }

    /**
     * @test
     * @group u-model
     * @dataProvider idleCapacityProvider
     *
     * @param mixed $kc
     * @param mixed $ku
     * @param mixed $expected
     */
    public function idleCapacity($kc, $ku, $expected): void
    {
        $mock = \Mockery::mock(KeyGeneratorService::class)->makePartial();
        $mock->shouldReceive([
            'maxCapacity'  => $kc,
            'usedCapacity' => $ku,
        ]);
        $actual = $mock->idleCapacity();

        $this->assertSame($expected, $actual);
    }

    public static function idleCapacityProvider(): array
    {
        // maxCapacity(), usedCapacity(), expected_result
        return [
            [1, 2, 0],
            [3, 2, 1],
            [100, 99, 1],
            [100, 20, 80],
            [100, 100, 0],
        ];
    }
}
