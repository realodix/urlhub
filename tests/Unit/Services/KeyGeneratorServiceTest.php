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
     * Pengujian untuk kondisi dimana panjang string yang diberikan tidak cocok
     * dengan panjang string yang telah ditentukan.
     *
     * - Panjang cocok: gunakan string tersebut.
     * - Panjang tidak cocok: generator harus menghasilkan string acak dengan
     *   panjang yang sesuai.
     *
     * @test
     * @group u-model
     */
    public function urlKey_string_lenght(): void
    {
        $actual = 'foobar';

        $strLen = 3;
        config(['urlhub.hash_length' => $strLen]);
        $this->assertSame(
            substr($actual, -$strLen),
            $this->keyGenerator->urlKey($actual)
        );

        $strLen = 8;
        config(['urlhub.hash_length' => $strLen]);
        $actual = strlen($this->keyGenerator->urlKey($actual));
        $this->assertSame($strLen, $actual);
        $this->assertNotSame(substr($actual, -$strLen), $actual);
    }


    /**
     * Pengujian untuk kondisi dimana panjang string yang diberikan pada `custom_key`
     * lebih pendek dari pada panjang string yang telah ditentukan.
     *
     * Maka:
     * - String tersebut harus digunakan.
     *
     * @test
     * @group u-model
     */
    public function urlKey_string_lenght2(): void
    {
        config(['urlhub.hash_length' => 10]);
        $longUrl = 'https://t.co';
        $customKey = 'tco';
        $response = $this->post(route('su_create'), [
            'long_url'   => $longUrl,
            'custom_key' => $customKey,
        ]);
        $response->assertRedirectToRoute('su_detail', $customKey);

        $url = Url::whereDestination($longUrl)->first();
        $this->assertTrue($url->is_custom);
    }

    /**
     * String dihasilkan dari pemotongan link dari belakang sepanjang panjang
     * karakter yang telah ditentukan.
     *
     * @test
     * @group u-model
     */
    public function urlKey_string_from_link_truncation(): void
    {
        $length = 3;
        config(['urlhub.hash_length' => $length]);

        $longUrl = 'https://github.com/realodix';
        $urlKey = $this->keyGenerator->urlKey($longUrl);

        $this->assertSame(substr($longUrl, -$length), $urlKey);
    }

    /**
     * String yang dihasilkan dari pemotongan tautan harus berupa abjad.
     *
     * @test
     * @group u-model
     */
    public function string_from_link_truncation_must_be_alphabet(): void
    {
        config(['urlhub.hash_length' => 3]);

        $this->assertSame('bar', $this->keyGenerator->generateSimpleString('foobar'));
        $this->assertSame('bar', $this->keyGenerator->generateSimpleString('foob/ar'));

        $this->assertSame('bar', $this->keyGenerator->generateSimpleString('fooBar'));
    }

    /**
     * Panjang string yang dihasilkan dari pemotongan link harus harus sesuai
     * dengan panjang yang telah ditentukan pada konfigurasi.
     *
     * @test
     * @group u-model
     */
    public function string_lenght_from_link_truncation_must_be_match_with_configured_length(): void
    {
        config(['urlhub.hash_length' => 6]);
        $actual = 'https://github.com/realodix';
        $expected = 'alodix';
        $this->assertSame($expected, $this->keyGenerator->generateSimpleString($actual));

        config(['urlhub.hash_length' => 9]);
        $actual = 'https://github.com/realodix';
        $expected = 'mrealodix';
        $this->assertSame($expected, $this->keyGenerator->generateSimpleString($actual));

        config(['urlhub.hash_length' => 12]);
        $actual = 'https://github.com/realodix';
        $expected = 'bcomrealodix';
        $this->assertSame($expected, $this->keyGenerator->generateSimpleString($actual));
    }

    /**
     * String yang dihasilkan dari pemotongan link harus berupa huruf kecil.
     *
     * @test
     * @group u-model
     */
    public function string_from_link_truncation_mus_be_lowercase(): void
    {
        $length = 4;
        config(['urlhub.hash_length' => $length]);

        $longUrl = 'https://github.com/realoDIX';
        $urlKey = $this->keyGenerator->generateSimpleString($longUrl);

        $this->assertSame(mb_strtolower(substr($longUrl, -$length)), $urlKey);
        $this->assertNotSame(substr($longUrl, -$length), $urlKey);
    }

    /**
     * Generator harus memberikan string yang belum digunakan. Jika string sudah
     * digunakan sebagai keyword, maka generator harus memberikan string unik
     * lainnya untuk `keyword`.
     *
     * @test
     * @group u-model
     */
    public function string_already_in_use(): void
    {
        $length = 3;
        config(['urlhub.hash_length' => $length]);

        $longUrl = 'https://github.com/realodix';
        Url::factory()->create(['keyword'  => $this->keyGenerator->urlKey($longUrl)]);

        $this->assertNotSame(substr($longUrl, -$length), $this->keyGenerator->urlKey($longUrl));
    }

    /**
     * Generator harus memberikan string yang tidak ada di dalam daftar reserved
     * keyword (config('urlhub.reserved_keyword')). Jika string ada di dalam daftar,
     * maka generator harus memberikan string unik lainnya untuk `keyword`.
     *
     * @test
     * @group u-model
     */
    public function string_is_reserved_keyword(): void
    {
        $actual = 'https://example.com/css';
        $expected = 'css';

        config(['urlhub.reserved_keyword' => [$expected]]);
        config(['urlhub.hash_length' => strlen($expected)]);

        $this->assertNotSame($expected, $this->keyGenerator->urlKey($actual));
    }

    /**
     * Generator harus memberikan string yang tidak ada di dalam daftar registered
     * route paths di Laravel. Jika string ada di dalam daftar, maka generator
     * harus memberikan string unik lainnya untuk `keyword`.
     *
     * Pada pengujian ini, string yang diberikan adalah 'login', dimana 'login'
     * sudah digunakan sebagai route path.
     *
     * @test
     * @group u-model
     */
    public function string_is_route_path(): void
    {
        $actual = 'https://example.com/login';
        $expected = 'login';

        config(['urlhub.hash_length' => strlen($expected)]);

        $this->assertNotSame($expected, $this->keyGenerator->urlKey($actual));
    }

    /**
     * @test
     * @group u-model
     */
    public function possibleOutput(): void
    {
        config(['urlhub.hash_length' => 2]);
        $this->assertSame(pow(62, 2), $this->keyGenerator->possibleOutput());
    }

    /**
     * Pengujian dilakukan berdasarkan panjang karakternya.
     *
     * @test
     * @group u-model
     */
    public function totalStringsUsedAsKeys(): void
    {
        config(['urlhub.hash_length' => config('urlhub.hash_length') + 1]);

        Url::factory()->create([
            'keyword' => $this->keyGenerator->generateRandomString(),
        ]);
        $this->assertSame(1, $this->keyGenerator->totalKey());

        Url::factory()->create([
            'keyword'   => str_repeat('a', config('urlhub.hash_length')),
            'is_custom' => true,
        ]);
        $this->assertSame(2, $this->keyGenerator->totalKey());

        // Karena panjang karakter 'keyword' berbeda dengan dengan 'urlhub.hash_length',
        // maka ini tidak ikut terhitung.
        Url::factory()->create([
            'keyword'   => str_repeat('b', config('urlhub.hash_length') + 2),
            'is_custom' => true,
        ]);
        $this->assertSame(2, $this->keyGenerator->totalKey());

        config(['urlhub.hash_length' => config('urlhub.hash_length') + 3]);
        $this->assertSame(0, $this->keyGenerator->totalKey());
        $this->assertSame($this->totalUrl, $this->url->count());
    }

    /**
     * @test
     * @group u-model
     * @dataProvider remainingCapacityProvider
     *
     * @param mixed $po
     * @param mixed $tk
     * @param mixed $expected
     */
    public function remainingCapacity($po, $tk, $expected): void
    {
        $mock = \Mockery::mock(KeyGeneratorService::class)->makePartial();
        $mock->shouldReceive([
            'possibleOutput' => $po,
            'totalKey'       => $tk,
        ]);
        $actual = $mock->remainingCapacity();

        $this->assertSame($expected, $actual);
    }

    public static function remainingCapacityProvider(): array
    {
        // possibleOutput(), totalKey(), expected_result
        return [
            [1, 2, 0],
            [3, 2, 1],
            [100, 99, 1],
            [100, 20, 80],
            [100, 100, 0],
        ];
    }
}
