<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

class NumHelperTest extends TestCase
{
    /**
     * @test
     * @group u-helper
     * @dataProvider readableInt
     */
    public function numberFormatShort($expected, $actual)
    {
        $this->assertSame($expected, numberFormatShort($actual));

        $int_or_str = numberFormatShort($actual);

        if (is_int($int_or_str)) {
            $this->assertIsInt($int_or_str);
        } else {
            $this->assertIsString($int_or_str);
        }
    }

    public function readableInt()
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
