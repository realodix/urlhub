<?php

namespace Tests\Unit\Rule;

use App\Rules\ShortUrlProtected;
use Tests\TestCase;

class ShortUrlProtectedTest extends TestCase
{
    protected $rule;

    public function setUp():void
    {
        parent::setUp();

        $this->rule = new ShortUrlProtected();
    }

    /**
     * @dataProvider ShortUrlProtectedPass
     * @param string $value
     * @return void
     */
    public function testShortUrlProtectedPass($value)
    {
        $this->assertTrue($this->rule->passes('test', $value));
    }

    /**
     * @dataProvider ShortUrlProtectedFail
     * @param string $value
     * @return void
     */
    public function testShortUrlProtectedFail($value)
    {
        $this->assertFalse($this->rule->passes('test', $value));
    }

    public function ShortUrlProtectedPass()
    {
        return [
            ['hello'],
            ['laravel'],
        ];
    }

    public function ShortUrlProtectedFail()
    {
        return [
            ['login'],
            ['register'],
        ];
    }
}
