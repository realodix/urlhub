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

    private KeyGeneratorService $keyGen;

    private int $totalUrl;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url;

        $this->keyGen = app(KeyGeneratorService::class);

        $this->totalUrl = self::N_URL_WITH_USER_ID + self::N_URL_WITHOUT_USER_ID;
    }

    public function testGenerateUniqueString(): void
    {
        $value = 'foo';

        // Scenario 1
        $hash = $this->keyGen->generate($value);
        $this->assertSame($this->keyGen->shortHash($value), $hash);

        // Scenario 2
        // If the string is already used as a short link keyword
        Url::factory()->create(['keyword' => $hash]);
        $mock = $this->partialMock(KeyGeneratorService::class);
        $mock->shouldReceive([
            'shortHash' => $hash,
            'randomString' => 'mocked_random_string',
        ]);

        $this->assertSame('mocked_random_string', $mock->generate($value));
    }

    public function testGenerateUniqueStringWithReservedKeyword(): void
    {
        $reserved_keyword = 'foo';
        config(['urlhub.reserved_keyword' => [$reserved_keyword]]);

        $mock = $this->partialMock(KeyGeneratorService::class);
        $mock->shouldReceive([
            'shortHash' => $reserved_keyword,
            'randomString' => 'mocked_random_string',
        ]);

        $this->assertSame('mocked_random_string', $mock->generate($reserved_keyword));
    }

    /**
     * The length of the string generated by the generator is adjustable
     */
    public function testGeneratorRespectsConfiguredLengt(): void
    {
        $inputString = 'foobar';
        $strLen = 8;
        settings()->fill(['key_len' => $strLen])->save();

        $actual = $this->keyGen->generate($inputString);
        $this->assertSame($strLen, strlen($actual));
        $this->assertNotSame(strlen($inputString), strlen($actual));
    }

    /**
     * The `verify` function should return `false` when the string is already
     * used as a short link keyword
     */
    public function testStringIsAlreadyUsedAsAShortLinkKeyword(): void
    {
        $value = $this->keyGen->generate('https://github.com/realodix');

        Url::factory()->create(['keyword' => $value]);

        $this->assertFalse($this->keyGen->verify($value));
    }

    /**
     * The `verify` function should return `false` when the string is in the
     * list of reserved keywords
     */
    public function testStringIsAReservedKeyword(): void
    {
        $value = 'foobar';

        config(['urlhub.reserved_keyword' => [$value]]);

        $this->assertFalse($this->keyGen->verify($value));
    }

    /**
     * The `verify` function should return `false` when the string is similar
     * to the route path name
     */
    public function testStringIsRegisteredRoute(): void
    {
        $route = collect($this->keyGen->routeCollisionList())
            ->first();

        $this->assertFalse($this->keyGen->verify($route));
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
        $this->assertFalse($this->keyGen->verify($value));
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
        $this->assertFalse($this->keyGen->verify($value));
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
        $this->assertEmpty($this->keyGen->reservedActiveKeyword()->all());

        // Test case 2: Some reserved keywords already in use
        $activeKeyword = self::RESOURCE_PREFIX.fake()->word();
        Url::factory()->create(['keyword' => $activeKeyword]);

        File::makeDirectory(public_path($activeKeyword));
        $this->assertEquals(
            $activeKeyword,
            $this->keyGen->reservedActiveKeyword()->implode(''),
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
        $charLen = strlen($this->keyGen::ALPHABET);

        settings()->fill(['key_len' => 2])->save();
        $this->assertSame(pow($charLen, 2), $this->keyGen->maxUniqueStrings());

        settings()->fill(['key_len' => 12])->save();
        $this->assertSame(PHP_INT_MAX, $this->keyGen->maxUniqueStrings());
    }

    /**
     * Pengujian dilakukan berdasarkan panjang karakternya.
     */
    public function testKeywordCountBasedOnStringLength(): void
    {
        $settings = app(\App\Settings\GeneralSettings::class);
        $keywordLength = $settings->key_len + 1;
        settings()->fill(['key_len' => $keywordLength])->save();

        Url::factory()->create([
            'keyword' => $this->keyGen->randomString(),
        ]);
        $this->assertSame(1, $this->keyGen->keywordCount());

        Url::factory()->create([
            'keyword' => str_repeat('a', $keywordLength),
            'is_custom' => true,
        ]);
        $this->assertSame(2, $this->keyGen->keywordCount());

        // Karena panjang karakter 'keyword' berbeda dengan dengan 'key_len',
        // maka ini tidak ikut terhitung.
        Url::factory()->create([
            'keyword' => str_repeat('b', $settings->key_len + 2),
            'is_custom' => true,
        ]);
        $this->assertSame(2, $this->keyGen->keywordCount());

        settings()->fill(['key_len' => $settings->key_len + 3])->save();
        $this->assertSame(0, $this->keyGen->keywordCount());
        $this->assertSame($this->totalUrl, $this->url->count());
    }

    /**
     * Only alphanumeric characters.
     */
    public function testKeywordCountBasedOnStringCharacters(): void
    {
        settings()->fill(['key_len' => 5])->save();

        Url::factory()->create(['keyword' => 'ab-cd']);
        $this->assertSame(0, $this->keyGen->keywordCount());
    }

    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('remainingCapacityProvider')]
    public function remainingCapacity($mus, $kc, $expected): void
    {
        $mock = $this->partialMock(KeyGeneratorService::class);
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
            $this->keyGen->filterCollisionCandidates($actual)->toArray(),
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
