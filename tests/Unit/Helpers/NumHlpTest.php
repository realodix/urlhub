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
        $this->assertSame($expected, $actual);
        $this->assertIsString($actual);
    }

    public function readableInt()
    {
        return [
            ['1K', readable_int(1000)],
            ['10K', readable_int(10000)],
            ['100K', readable_int(100000)],

            ['1M', readable_int(1000000)],
            ['10M', readable_int(10000000)],
            ['100M', readable_int(100000000)],

            ['1B', readable_int(1000000000)],
            ['10B', readable_int(10000000000)],
            ['100B', readable_int(100000000000)],

            ['1T', readable_int(1000000000000)],
            ['10T', readable_int(10000000000000)],
            ['100T', readable_int(100000000000000)],

            ['12K+', readable_int(12345)],
            ['12M+', readable_int(12345678)],
            ['1B+', readable_int(1234567890)],
            ['1T+', readable_int(1234567890000)],
        ];
    }

    public function test_readable_int_input_num()
    {
        $this->assertSame('12', readable_int(12));
        $this->assertSame('12', readable_int(12.3));
    }

    public function test_readable_int_input_str()
    {
        $this->assertSame('10', readable_int('10'));
        $this->assertSame('1K', readable_int('1000'));
        $this->assertIsString(readable_int('1000'));
    }

    public function test_number_format_precision()
    {
        $this->assertSame(10, NumHlp::number_format_precision(10.0));
        $this->assertSame(10, NumHlp::number_format_precision(10.00));
        $this->assertSame(10.11, NumHlp::number_format_precision(10.111));
        $this->assertSame(10.127, NumHlp::number_format_precision(10.1279, 3));
    }
}
