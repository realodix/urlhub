<?php

namespace Tests\Unit\Helpers;

use App\Helpers\Helper;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('helper')]
class HelperTest extends TestCase
{
    #[PHPUnit\Test]
    public function urlFormat(): void
    {
        $this->assertSame(
            'https://example.com/abcde/',
            Helper::urlFormat('https://example.com/abcde/'),
        );

        $this->assertSame(
            'https://example.com',
            Helper::urlFormat('https://example.com/', trailingSlash: false),
        );

        $url = 'https://github.com/laravel/framework/commit/de69bb287c5017d1acb7d47a6db1dedf578036d6';

        $this->assertSame(
            'https://github.com/laravel/fra...',
            Helper::urlFormat($url, limit: 33),
        );

        $this->assertSame(
            'github.com/laravel/framework/c...',
            Helper::urlFormat($url, scheme: false, limit: 33),
        );
    }

    /**
     * Test the urlFormat() method with too long host.
     */
    #[PHPUnit\Test]
    public function urlFormatWithTooLongHost(): void
    {
        $url = 'http://theofficialabsolutelongestdomainnameregisteredontheworldwideweb.international/search?client=firefox-b-d&q=longets+domain';

        $this->assertSame(
            'http://theofficialabsolutelongestdomainnameregisteredontheworldwidewe...&q=longets+domain',
            Helper::urlFormat($url, limit: 90),
        );

        $this->assertSame(
            'theofficialabsolutelongestdomainnameregisteredontheworldwideweb....q=longets+domain',
            Helper::urlFormat($url, scheme: false, limit: 84),
        );

        $this->assertSame(
            'https://hunterxhunter...unter',
            Helper::urlFormat('https://hunterxhunter.fandom.com/wiki/Hunter_%C3%97_Hunter', limit: 30),
        );
    }

    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('urlFormatWithoutSchemeProvider')]
    public function urlFormatWithoutScheme($expected, $actual): void
    {
        $this->assertSame($expected, Helper::urlFormat($actual, scheme: false));
    }

    public static function urlFormatWithoutSchemeProvider(): array
    {
        return [
            ['example.com', 'example.com'],
            ['www.example.com', 'www.example.com'],
            ['example.com', 'http://example.com'],
            ['www.example.com', 'http://www.example.com'],
            ['example.com', 'https://example.com'],
            ['www.example.com', 'https://www.example.com'],
            ['www.example.com/abc', 'https://www.example.com/abc'],
        ];
    }

    public function test_n_abb(): void
    {
        $this->assertSame('7K', \Illuminate\Support\Number::abbreviate(6789));

        $this->assertSame('6.79K', \Illuminate\Support\Number::abbreviate(6789, maxPrecision: 2));
        $this->assertSame('6.79K', n_abb(6789));
    }
}
