<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

class GeneralHelperTest extends TestCase
{
    /**
     * @group u-helper
     */
    public function testAppName()
    {
        $expected = config('app.name');
        $actual = app_name();
        $this->assertSame($expected, $actual);
    }
}
