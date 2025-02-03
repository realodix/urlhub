<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Services\KeyGeneratorService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class KeyGeneratorServiceTest extends TestCase
{
    private const N_URL_WITH_USER_ID = 1;
    private const N_URL_WITHOUT_USER_ID = 2;

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

        $urlFactory = Url::factory()->create(['keyword' => $hash]);
        $hash2 = $this->keyGenerator->generate($value1);
        $this->assertSame(strtoupper($this->keyGenerator->shortHash($value1)), $hash2);

        Url::factory()->create(['keyword' => $hash2]);
        $hash3 = $this->keyGenerator->generate($value1);
        $this->assertSame(
            $this->keyGenerator->shortHash($value1 . $urlFactory->latest('id')->value('id')),
            $hash3,
        );

        Url::factory()->create(['keyword' => $hash3]);
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
     * Pengujian untuk kondisi dimana panjang string yang diberikan pada `custom_key`
     * lebih pendek dari pada panjang string yang telah ditentukan.
     *
     * Maka:
     * - String tersebut harus digunakan.
     */
    #[PHPUnit\Test]
    public function urlKey_string_lenght2(): void
    {
        settings()->fill(['keyword_length' => 10])->save();

        $longUrl = 'https://t.co';
        $customKey = 'tco';
        $response = $this->post(route('link.create'), [
            'long_url' => $longUrl,
            'custom_key' => $customKey,
        ]);
        $response->assertRedirectToRoute('link_detail', $customKey);

        $url = Url::whereDestination($longUrl)->first();
        $this->assertTrue($url->is_custom);
    }

    /**
     * Tests whether the verify function returns a false value if the given string
     * is already used as the active keyword.
     *
     * The verify function should return a false value if the given string is
     * already used as the active keyword.
     */
    public function testStringIsAlreadyUsedAsTheActiveKeyword(): void
    {
        settings()->fill(['keyword_length' => 5])->save();

        $value = $this->keyGenerator->generate('https://github.com/realodix');

        Url::factory()->create(['keyword' => $value]);

        $this->assertFalse($this->keyGenerator->verify($value));
    }

    /**
     * Tests whether the verify function returns a false value if the given string
     * is a reserved keyword.
     *
     * The verify function should return a false value if the given string is
     * a reserved keyword.
     */
    public function testStringIsAReservedKeyword(): void
    {
        $value = 'foobar';

        config(['urlhub.reserved_keyword' => [$value]]);

        $this->assertFalse($this->keyGenerator->verify($value));
    }

    /**
     * Tests whether the verify function returns a false value if the given string
     * is a registered route.
     *
     * The verify function should return a false value if the given string is
     * a registered route.
     */
    public function testStringIsRegisteredRoute(): void
    {
        $value = 'admin';

        $this->assertFalse($this->keyGenerator->verify($value));
    }

    /**
     * If the keyword is the same as the name of a public path, then it
     * shouldn't be used as a keyword.
     */
    public function testStringIsPublicPath(): void
    {
        $fileSystem = new \Illuminate\Filesystem\Filesystem;
        $value = 'zzz' . fake()->word();

        $fileSystem->makeDirectory(public_path($value));
        $this->assertFalse($this->keyGenerator->verify($value));
        $fileSystem->deleteDirectory(public_path($value));
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
        $fileSystem = new \Illuminate\Filesystem\Filesystem;

        // Test case 1: No reserved keywords already in use
        $this->assertEquals(
            new \Illuminate\Support\Collection,
            $this->keyGenerator->reservedActiveKeyword(),
        );

        // Test case 2: Some reserved keywords already in use
        $activeKeyword = 'zzz' . fake()->word();
        Url::factory()->create(['keyword' => $activeKeyword]);

        $fileSystem->makeDirectory(public_path($activeKeyword));
        $this->assertEquals(
            $activeKeyword,
            $this->keyGenerator->reservedActiveKeyword()->implode(''),
        );
        $fileSystem->deleteDirectory(public_path($activeKeyword));
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

        settings()->fill(['keyword_length' => 11])->save();
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

    public function testCollisionCandidateFilter(): void
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
            $this->keyGenerator->collisionCandidateFilter($actual)->toArray(),
        );
    }
}
