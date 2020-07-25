<?php

namespace Tests\Unit\Services;

use App\Services\ConfigService;
use Tests\TestCase;

class ConfigServiceTest extends TestCase
{
    /**
     * @test
     * @group u-service
     * @dataProvider hashCharProvider
     */
    public function hash_char($value, $expected)
    {
        config(['urlhub.hash_char' => $value]);

        (new ConfigService)->configGuard();

        $this->assertSame($expected, uHub('hash_char'));
    }

    public function hashCharProvider()
    {
        $defaultHashChar = ConfigService::DEFAULT_HASH_CHAR;

        return [
            ['string', 'string'],
            ['string=+', $defaultHashChar], // non-alphanumeric
            ['', $defaultHashChar],
            [true, $defaultHashChar],
        ];
    }
}
