<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\General\NumHelper;

class GeneralHelperTest extends TestCase
{
    /**
     * @group u-helper
     */
    public function testUHub()
    {
        $expected = config('urlhub.hash_length');
        $actual = uHub('hash_length');
        $this->assertSame($expected, $actual);
    }

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
     * @group u-helper
     * @test
     * @dataProvider toAmountShortProvider
     */
    public function toAmountShort($expected, $actual)
    {
        $this->assertSame($expected, (new NumHelper)->toAmountShort($actual));

        $intOrString = (new NumHelper)->toAmountShort($actual);

        if (is_int($intOrString)) {
            $this->assertIsInt($intOrString);
        } else {
            $this->assertIsString($intOrString);
        }
    }

    /**
     * @group u-helper
     * @test
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
