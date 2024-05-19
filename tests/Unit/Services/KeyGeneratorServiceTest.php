<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Services\KeyGeneratorService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
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
     * String dihasilkan dari pemotongan link dari belakang sepanjang panjang
     * karakter yang telah ditentukan.
     */
    #[PHPUnit\Test]
    public function keyword_test(): void
    {
        $length = 3;
        config(['urlhub.keyword_length' => $length]);

        $longUrl = 'https://github.com/realodix';
        $urlKey = $this->keyGenerator->generate($longUrl);

        $this->assertSame(substr($longUrl, -$length), $urlKey);
    }

    /**
     * UrlKey dihasilkan dari hasil pemotongan string URL. Sayangnya terkadang
     * panjang string dari hasil pemotongan tersebut bisa lebih pendek daripada
     * panjang yang harapkan. Ketika itu terjadi, maka generator harus menghasilkan
     * string acak dengan panjang yang sesuai dengan yang diharapkan.
     */
    #[PHPUnit\Test]
    public function urlKey_string_lenght(): void
    {
        $inputString = 'foobar';

        // configured_strlen > input_strlen
        // Generator harus menghasilkan string acak dengan panjang yang sesuai.
        $strLen = 8;
        config(['urlhub.keyword_length' => $strLen]);
        $actual = $this->keyGenerator->generate($inputString);
        $this->assertSame($strLen, strlen($actual));
        $this->assertNotSame(strlen($inputString), strlen($actual));
    }

    /**
     * Pengujian untuk kondisi dimana panjang string yang diberikan pada `custom_key`
     * lebih pendek dari pada panjang string yang telah ditentukan.
     *
     * Maka:
     * - String tersebut harus digunakan.
     */
    #[PHPUnit\Test]
    public function urlKey_string_lenght2(): void
    {
        config(['urlhub.keyword_length' => 10]);
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
     * String yang dihasilkan dari pemotongan tautan harus berupa abjad.
     */
    #[PHPUnit\Test]
    #[PHPUnit\TestWith(['ravel', 'https://github.com/laravel/laravel'])]
    #[PHPUnit\TestWith(['ravel', 'https://en.wikipedia.org/wiki/Laravel'])]
    #[PHPUnit\TestWith(['uting', 'https://laravel.com/docs/11.x/routing'])]
    #[PHPUnit\TestWith(['uting', 'https://en.wikipedia.org/wiki/Routing'])]
    public function generateSimpleString($expected, $actual): void
    {
        config(['urlhub.keyword_length' => 5]);

        $this->assertSame($expected, $this->keyGenerator->generateSimpleString($actual));
    }

    /**
     * String yang dihasilkan dari pemotongan tautan harus berupa abjad.
     */
    #[PHPUnit\Test]
    public function generateSimpleString_must_be_alphabet(): void
    {
        config(['urlhub.keyword_length' => 3]);

        $this->assertSame('bar', $this->keyGenerator->generateSimpleString('foobar'));
        $this->assertSame('bar', $this->keyGenerator->generateSimpleString('foob/ar'));

        $this->assertSame('bar', $this->keyGenerator->generateSimpleString('fooBar'));
    }

    /**
     * Panjang string yang dihasilkan dari pemotongan link harus harus sesuai
     * dengan panjang yang telah ditentukan pada konfigurasi.
     */
    #[PHPUnit\Test]
    public function generateSimpleString_string_length(): void
    {
        config(['urlhub.keyword_length' => 6]);
        $actual = 'https://github.com/realodix';
        $expected = 'alodix';
        $this->assertSame($expected, $this->keyGenerator->generateSimpleString($actual));

        config(['urlhub.keyword_length' => 9]);
        $actual = 'https://github.com/realodix';
        $expected = 'mrealodix';
        $this->assertSame($expected, $this->keyGenerator->generateSimpleString($actual));

        config(['urlhub.keyword_length' => 12]);
        $actual = 'https://github.com/realodix';
        $expected = 'bcomrealodix';
        $this->assertSame($expected, $this->keyGenerator->generateSimpleString($actual));
    }

    /**
     * String yang dihasilkan dari pemotongan link harus berupa huruf kecil.
     */
    #[PHPUnit\Test]
    public function generateSimpleString_mus_be_lowercase(): void
    {
        $length = 4;
        config(['urlhub.keyword_length' => $length]);

        $longUrl = 'https://github.com/realoDIX';
        $urlKey = $this->keyGenerator->generateSimpleString($longUrl);

        $this->assertSame(mb_strtolower(substr($longUrl, -$length)), $urlKey);
        $this->assertNotSame(substr($longUrl, -$length), $urlKey);
    }

    /**
     * Generator harus memberikan string yang belum digunakan. Jika string sudah
     * digunakan sebagai keyword, maka generator harus memberikan string unik
     * lainnya untuk `keyword`.
     */
    #[PHPUnit\Test]
    public function string_already_in_use(): void
    {
        $length = 3;
        config(['urlhub.keyword_length' => $length]);

        $longUrl = 'https://github.com/realodix';
        Url::factory()->create(['keyword'  => $this->keyGenerator->generate($longUrl)]);

        $this->assertNotSame(substr($longUrl, -$length), $this->keyGenerator->generate($longUrl));
    }

    /**
     * Generator harus memberikan string yang tidak ada di dalam daftar reserved
     * keyword (config('urlhub.reserved_keyword')). Jika string ada di dalam daftar,
     * maka generator harus memberikan string unik lainnya untuk `keyword`.
     */
    #[PHPUnit\Test]
    public function string_is_reserved_keyword(): void
    {
        $actual = 'https://example.com/css';
        $expected = 'css';

        config(['urlhub.reserved_keyword' => [$expected]]);
        config(['urlhub.keyword_length' => strlen($expected)]);

        $this->assertNotSame($expected, $this->keyGenerator->generate($actual));
    }

    /**
     * Generator harus memberikan string yang tidak ada di dalam daftar registered
     * route paths di Laravel. Jika string ada di dalam daftar, maka generator
     * harus memberikan string unik lainnya untuk `keyword`.
     *
     * Pada pengujian ini, string yang diberikan adalah 'login', dimana 'login'
     * sudah digunakan sebagai route path.
     */
    #[PHPUnit\Test]
    public function string_is_route_path(): void
    {
        $actual = 'https://example.com/login';
        $expected = 'login';

        config(['urlhub.keyword_length' => strlen($expected)]);

        $this->assertNotSame($expected, $this->keyGenerator->generate($actual));
    }

    #[PHPUnit\Test]
    public function possibleOutput(): void
    {
        $charLen = strlen($this->keyGenerator::ALPHABET);

        config(['urlhub.keyword_length' => 2]);
        $this->assertSame(pow($charLen, 2), $this->keyGenerator->possibleOutput());

        if (! extension_loaded('gmp')) {
            $this->markTestSkipped('The GMP extension is not available.');
        }
        config(['urlhub.keyword_length' => 11]);
        $this->assertSame(
            gmp_intval(gmp_pow($charLen, 11)),
            $this->keyGenerator->possibleOutput()
        );
    }

    /**
     * Pengujian dilakukan berdasarkan panjang karakternya.
     */
    #[PHPUnit\Test]
    public function totalStringsUsedAsKeys(): void
    {
        config(['urlhub.keyword_length' => config('urlhub.keyword_length') + 1]);

        Url::factory()->create([
            'keyword' => $this->keyGenerator->generateRandomString(),
        ]);
        $this->assertSame(1, $this->keyGenerator->totalKey());

        Url::factory()->create([
            'keyword'   => str_repeat('a', config('urlhub.keyword_length')),
            'is_custom' => true,
        ]);
        $this->assertSame(2, $this->keyGenerator->totalKey());

        // Karena panjang karakter 'keyword' berbeda dengan dengan 'urlhub.keyword_length',
        // maka ini tidak ikut terhitung.
        Url::factory()->create([
            'keyword'   => str_repeat('b', config('urlhub.keyword_length') + 2),
            'is_custom' => true,
        ]);
        $this->assertSame(2, $this->keyGenerator->totalKey());

        config(['urlhub.keyword_length' => config('urlhub.keyword_length') + 3]);
        $this->assertSame(0, $this->keyGenerator->totalKey());
        $this->assertSame($this->totalUrl, $this->url->count());
    }

    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('remainingCapacityProvider')]
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
