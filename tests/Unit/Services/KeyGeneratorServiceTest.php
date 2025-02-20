<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Services\KeyGeneratorService;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class KeyGeneratorServiceTest extends TestCase
{
    private const N_URL_WITH_USER_ID = 1;
    private const N_URL_WITHOUT_USER_ID = 2;
    private const RESOURCE_PREFIX = 'zzz';

    private Url $url;

    private KeyGeneratorService $keyGenerator;

    private int $totalUrl;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url;

        $this->keyGenerator = app(KeyGeneratorService::class);

        $this->totalUrl = self::N_URL_WITH_USER_ID + self::N_URL_WITHOUT_USER_ID;
    }

    public function testGenerateUniqueString(): void
    {
        $value1 = 'foo';

        $hash = $this->keyGenerator->generate($value1);
        $this->assertSame($this->keyGenerator->shortHash($value1), $hash);

        Url::factory()->create(['keyword' => $hash]);
        $hash2 = $this->keyGenerator->generate($value1);
        $this->assertSame(strtoupper($this->keyGenerator->shortHash($value1)), $hash2);

        Url::factory()->create(['keyword' => $hash2]);
        $this->assertNotSame($hash2, $this->keyGenerator->generate($value1));
    }

    public function testGenerateUniqueStringWithReservedKeyword(): void
    {
        $value1 = 'foo';
        $generatedString1 = $this->keyGenerator->generate($value1);
        Url::factory()->create(['keyword' => $generatedString1]);
        $this->assertSame($this->keyGenerator->shortHash($value1), $generatedString1);
        $this->assertNotSame($generatedString1, $this->keyGenerator->generate($value1));

        $value2 = 'foo2';
        $generatedString2 = $this->keyGenerator->generate($value2);
        config(['urlhub.reserved_keyword' => [$generatedString2]]);
        $this->assertSame($this->keyGenerator->shortHash($value2), $generatedString2);
        $this->assertNotSame($generatedString2, $this->keyGenerator->generate($value2));
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
        settings()->fill(['keyword_length' => $strLen])->save();
        $actual = $this->keyGenerator->generate($inputString);

        $this->assertSame($strLen, strlen($actual));
        $this->assertNotSame(strlen($inputString), strlen($actual));
    }

    /**
     * The `verify` function should return `false` when the string is already
     * used as a short link keyword
     */
    public function testStringIsAlreadyUsedAsAShortLinkKeyword(): void
    {
        $value = $this->keyGenerator->generate('https://github.com/realodix');

        Url::factory()->create(['keyword' => $value]);

        $this->assertFalse($this->keyGenerator->verify($value));
    }

    /**
     * The `verify` function should return `false` when the string is in the
     * list of reserved keywords
     */
    public function testStringIsAReservedKeyword(): void
    {
        $value = 'foobar';

        config(['urlhub.reserved_keyword' => [$value]]);

        $this->assertFalse($this->keyGenerator->verify($value));
    }

    /**
     * The `verify` function should return `false` when the string is similar
     * to the route path name
     */
    public function testStringIsRegisteredRoute(): void
    {
        $route = collect($this->keyGenerator->routeCollisionList())
            ->first();

        $this->assertFalse($this->keyGenerator->verify($route));
    }

    /**
     * The `verify` function should return `false` when the string is a valid
     * directory name in the public directory
     */
    #[PHPUnit\Test]
    public function stringIsADirectoryInsideThePubicDirectory(): void
    {
        $value = self::RESOURCE_PREFIX.fake()->word();

        File::makeDirectory(public_path($value));
        $this->assertFalse($this->keyGenerator->verify($value));
    }

    /**
     * The `verify` function should return `false` when the string is a valid
     * file name in the public directory
     */
    #[PHPUnit\Test]
    public function stringIsAFileInsideThePublicDirectory(): void
    {
        $value = self::RESOURCE_PREFIX.fake()->word();

        File::put(public_path($value), '');
        $this->assertFalse($this->keyGenerator->verify($value));
    }

    /**
     * Menguji apakah fungsi reservedActiveKeyword mengembalikan nilai yang sesuai.
     *
     * reservedActiveKeyword mengembalikan keyword yang terdaftar sebagai reserved
     * keyword dan sudah digunakan sebagai custom keyword.
     *
     * Kondisi 1: Belum ada reserved keyword yang digunakan.
     * Kondisi 2: Ada beberapa reserved keyword yang sudah digunakan.
     */
    public function testReservedActiveKeyword()
    {
        // Test case 1: No reserved keywords already in use
        $this->assertEquals(
            new \Illuminate\Support\Collection,
            $this->keyGenerator->reservedActiveKeyword(),
        );

        // Test case 2: Some reserved keywords already in use
        $activeKeyword = self::RESOURCE_PREFIX.fake()->word();
        Url::factory()->create(['keyword' => $activeKeyword]);

        File::makeDirectory(public_path($activeKeyword));
        $this->assertEquals(
            $activeKeyword,
            $this->keyGenerator->reservedActiveKeyword()->implode(''),
        );
    }

    /**
     * Menguji apakah fungsi maxUniqueStrings mengembalikan nilai yang sesuai.
     *
     * maxUniqueStrings mengembalikan jumlah kombinasi string yang mungkin
     * dihasilkan oleh generator keyword. Jika panjang keyword yang dihasilkan
     * terlalu panjang maka fungsi ini mengembalikan nilai PHP_INT_MAX.
     *
     * Kondisi 1: Panjang keyword yang dihasilkan relatif pendek.
     * Kondisi 2: Panjang keyword yang dihasilkan relatif panjang.
     */
    #[PHPUnit\Test]
    public function maxUniqueStrings(): void
    {
        $charLen = strlen($this->keyGenerator::ALPHABET);

        settings()->fill(['keyword_length' => 2])->save();
        $this->assertSame(pow($charLen, 2), $this->keyGenerator->maxUniqueStrings());

        settings()->fill(['keyword_length' => 12])->save();
        $this->assertSame(PHP_INT_MAX, $this->keyGenerator->maxUniqueStrings());
    }

    /**
     * Pengujian dilakukan berdasarkan panjang karakternya.
     */
    public function testKeywordCountBasedOnStringLength(): void
    {
        $settings = app(\App\Settings\GeneralSettings::class);
        $keywordLength = $settings->keyword_length + 1;
        settings()->fill(['keyword_length' => $keywordLength])->save();

        Url::factory()->create([
            'keyword' => $this->keyGenerator->randomString(),
        ]);
        $this->assertSame(1, $this->keyGenerator->keywordCount());

        Url::factory()->create([
            'keyword'   => str_repeat('a', $keywordLength),
            'is_custom' => true,
        ]);
        $this->assertSame(2, $this->keyGenerator->keywordCount());

        // Karena panjang karakter 'keyword' berbeda dengan dengan 'keyword_length',
        // maka ini tidak ikut terhitung.
        Url::factory()->create([
            'keyword'   => str_repeat('b', $settings->keyword_length + 2),
            'is_custom' => true,
        ]);
        $this->assertSame(2, $this->keyGenerator->keywordCount());

        settings()->fill(['keyword_length' => $settings->keyword_length + 3])->save();
        $this->assertSame(0, $this->keyGenerator->keywordCount());
        $this->assertSame($this->totalUrl, $this->url->count());
    }

    /**
     * Only alphanumeric characters.
     */
    public function testKeywordCountBasedOnStringCharacters(): void
    {
        settings()->fill(['keyword_length' => 5])->save();

        Url::factory()->create([
            'keyword' => 'ab-cd',
        ]);
        $this->assertSame(0, $this->keyGenerator->keywordCount());
    }

    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('remainingCapacityProvider')]
    public function remainingCapacity($mus, $kc, $expected): void
    {
        $mock = \Mockery::mock(KeyGeneratorService::class)->makePartial();
        $mock->shouldReceive([
            'maxUniqueStrings' => $mus,
            'keywordCount' => $kc,
        ]);
        $actual = $mock->remainingCapacity();

        $this->assertSame($expected, $actual);
    }

    public static function remainingCapacityProvider(): array
    {
        // maxUniqueStrings(), keywordCount(), expected_result
        return [
            [1, 2, 0],
            [3, 2, 1],
            [100, 99, 1],
            [100, 20, 80],
            [100, 100, 0],
        ];
    }

    #[PHPUnit\Test]
    public function filterCollisionCandidates(): void
    {
        $actual = array_merge(
            [
                'css',
                'reset-password',

                '.',
                '..',
                '.htaccess',
                'favicon.ico',

                '+{url}',
                '/',
                '_debugbar',
                '_debugbar/assets/javascript',
                'admin/about',
                'admin/user/{user}/changepassword',
                'admin/links/u/{user}',
            ],
            config('urlhub.reserved_keyword'),
        );

        $expected = ['css', 'reset-password'];

        $this->assertEquals(
            $expected,
            $this->keyGenerator->filterCollisionCandidates($actual)->toArray(),
        );
    }

    public function tearDown(): void
    {
        $resources = File::glob(public_path(self::RESOURCE_PREFIX.'*'));
        foreach ($resources as $resource) {
            File::deleteDirectory($resource);
            File::delete($resource);
        }

        parent::tearDown();
    }
}
