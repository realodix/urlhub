<?php

namespace Tests\Unit;

use Facades\App\Helpers\NumHlp;
use Tests\TestCase;

class NumHlpTest extends TestCase
{
    /**
     * @dataProvider readableInt
     */
    public function test_readable_int($expected, $actual)
    {
        $this->assertSame($expected, readable_int($actual));

        $int_or_str = readable_int($actual);

        if (is_integer($int_or_str)) {
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

            ['1M', pow(10, 6)],
            ['10M', pow(10, 7)],
            ['100M', pow(10, 8)],

            ['1B', pow(10, 9)],
            ['10B', pow(10, 10)],
            ['100B', pow(10, 11)],

            ['1T', pow(10, 12)],
            ['10T', pow(10, 13)],
            ['100T', pow(10, 14)],

            ['12K+', 12345],
            ['12M+', 12345678],
            ['1B+', 1234567890],
            ['1T+', 1234567890000],
        ];
    }

    public function test_number_format_precision()
    {
        $this->assertSame(10, NumHlp::number_format_precision(10.0));
        $this->assertSame(10, NumHlp::number_format_precision(10.00));
        $this->assertSame(10.11, NumHlp::number_format_precision(10.111));
        $this->assertSame(10.127, NumHlp::number_format_precision(10.1279, 3));
    }
}
