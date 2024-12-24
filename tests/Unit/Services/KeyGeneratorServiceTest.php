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
        $generatedString1 = $this->keyGenerator->generate($value1);
        Url::factory()->create(['keyword' => $generatedString1]);
        $this->assertSame($this->keyGenerator->simpleString($value1), $generatedString1);
        $this->assertNotSame($generatedString1, $this->keyGenerator->generate($value1));

        $value2 = 'foo2';
        $generatedString2 = $this->keyGenerator->generate($value2);
        config(['urlhub.reserved_keyword' => [$generatedString2]]);
        $this->assertSame($this->keyGenerator->simpleString($value2), $generatedString2);
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
        config(['urlhub.keyword_length' => 5]);
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
     * Menguji apakah fungsi possibleOutput mengembalikan nilai yang sesuai.
     *
     * possibleOutput mengembalikan jumlah kombinasi string yang mungkin
     * dihasilkan oleh generator keyword. Jika panjang keyword yang dihasilkan
     * terlalu panjang maka fungsi ini mengembalikan nilai PHP_INT_MAX.
     *
     * Kondisi 1: Panjang keyword yang dihasilkan relatif pendek.
     * Kondisi 2: Panjang keyword yang dihasilkan relatif panjang.
     */
    #[PHPUnit\Test]
    public function possibleOutput(): void
    {
        $charLen = strlen($this->keyGenerator::ALPHABET);

        config(['urlhub.keyword_length' => 2]);
        $this->assertSame(pow($charLen, 2), $this->keyGenerator->possibleOutput());

        config(['urlhub.keyword_length' => 11]);
        $this->assertSame(PHP_INT_MAX, $this->keyGenerator->possibleOutput());
    }

    /**
     * Pengujian dilakukan berdasarkan panjang karakternya.
     */
    public function testTotalKeyBasedOnStringLength(): void
    {
        config(['urlhub.keyword_length' => config('urlhub.keyword_length') + 1]);

        Url::factory()->create([
            'keyword' => $this->keyGenerator->randomString(),
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

    /**
     * Only alphanumeric characters.
     */
    public function testTotalKeysBasedOnStringCharacters(): void
    {
        config(['urlhub.keyword_length' => 5]);

        Url::factory()->create([
            'keyword' => 'ab-cd',
        ]);
        $this->assertSame(0, $this->keyGenerator->totalKey());
    }

    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('remainingCapacityProvider')]
    public function remainingCapacity($po, $tk, $expected): void
    {
        $mock = \Mockery::mock(KeyGeneratorService::class)->makePartial();
        $mock->shouldReceive([
            'possibleOutput' => $po,
            'totalKey' => $tk,
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
