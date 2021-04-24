<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

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
}
