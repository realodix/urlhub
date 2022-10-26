<?php

namespace Tests\Unit\Helpers;

use App\Helpers\General\{Helper, NumHelper};
use Tests\TestCase;

class HelperTest extends TestCase
{
    /**
     * @group u-helper
     */
    public function testAppName()
    {
        $expected = config('app.name');
        $actual = appName();
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function urlDisplay()
    {
        $this->assertSame(
            'https://example.com/abcde/',
            Helper::urlDisplay('https://example.com/abcde/')
        );

        $this->assertSame(
            'example.com/abcde',
            Helper::urlDisplay('https://example.com/abcde/', false)
        );

        $this->assertSame(
            'https://example.com',
            Helper::urlDisplay('https://example.com/')
        );

        $this->assertSame(
            'https://github.com/real...e0be',
            Helper::urlDisplay('https://github.com/realodix/urlhub/commit/33e6d649d2d18345ac2d53a2fe553ae5d174e0be', limit: 30)
        );
    }

    /**
     * @test
     * @dataProvider sanitizeProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function sanitize($expected, $actual)
    {
        $this->assertSame($expected, Helper::urlSanitize($actual));
    }

    public function sanitizeProvider()
    {
        return [
            ['laravel.com', 'laravel.com'],
            ['laravel.com', 'www.laravel.com'],
            ['laravel.com', 'http://laravel.com'],
            ['laravel.com', 'http://www.laravel.com'],
            ['laravel.com', 'https://laravel.com'],
            ['laravel.com', 'https://www.laravel.com'],
            ['laravel.com', 'https://www.laravel.com/'],
            ['laravel.com/abc', 'https://www.laravel.com/abc'],
            ['laravel.com/abc', 'https://www.laravel.com/abc/'],
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
    public function numberToAmountShort($expected, $actual)
    {
        $this->assertSame($expected, (new NumHelper)->numberToAmountShort($actual));

        $intOrString = (new NumHelper)->numberToAmountShort($actual);

        if (is_int($intOrString)) {
            $this->assertIsInt($intOrString);
        } else {
            $this->assertIsString($intOrString);
        }
    }

    /**
     * @test
     * @group u-helper
     */
    public function numbPrec()
    {
        $this->assertSame(19.12, (new NumHelper)->numbPrec(19.123456));
        $this->assertSame(19.123, (new NumHelper)->numbPrec(19.123456, 3));
    }

    public function toAmountShortProvider()
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
