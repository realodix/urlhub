<?php

namespace Tests\Unit\Helpers;

use App\Helpers\Helper;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('helper')]
class HelperTest extends TestCase
{
    #[PHPUnit\Test]
    public function urlDisplay(): void
    {
        $this->assertSame(
            'https://example.com/abcde/',
            Helper::urlDisplay('https://example.com/abcde/')
        );

        $this->assertSame(
            'https://example.com',
            Helper::urlDisplay('https://example.com/', trailingSlash: false)
        );

        $this->assertSame(
            'https://github.com/real...e0be',
            Helper::urlDisplay('https://github.com/realodix/urlhub/commit/33e6d649d2d18345ac2d53a2fe553ae5d174e0be', limit: 30)
        );
    }

    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('urlDisplayWithoutSchemeProvider')]
    public function urlDisplayWithoutScheme($expected, $actual): void
    {
        $this->assertSame($expected, Helper::urlDisplay($actual, scheme: false));
    }

    public static function urlDisplayWithoutSchemeProvider(): array
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
