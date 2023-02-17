<?php

namespace Tests\Unit\Helpers;

use App\Helpers\Helper;
use Tests\TestCase;

class HelperTest extends TestCase
{
    /** @test */
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

    /**
     * @test
     * @dataProvider urlDisplayWithoutSchemeProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
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

    /**
     * @test
     * @group u-helper
     * @dataProvider toAmountShortProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function compactNumber($expected, $actual): void
    {
        $this->assertSame($expected, Helper::compactNumber($actual));
    }

    /**
     * @test
     * @group u-helper
     */
    public function numberFormatPrecision(): void
    {
        $this->assertSame(19.12, Helper::numberFormatPrecision(19.123456));
        $this->assertSame(19.123, Helper::numberFormatPrecision(19.123456, 3));
    }

    public static function toAmountShortProvider(): array
    {
        return [
            ['12', 12],
            ['12', 12.3],

            ['1K', pow(10, 3)],
            ['10K', pow(10, 4)],
            ['100K', pow(10, 5)],
            ['12.34K+', 12345],

            ['1M', pow(10, 6)],
            ['10M', pow(10, 7)],
            ['100M', pow(10, 8)],
            ['99.99M+', 99997092],

            ['1B', pow(10, 9)],
            ['10B', pow(10, 10)],
            ['100B', pow(10, 11)],
            ['1.23B+', 1234567890],

            ['1T', pow(10, 12)],
            ['10T', pow(10, 13)],
            ['100T', pow(10, 14)],
            ['1.23T+', 1234567890000],
        ];
    }
}
