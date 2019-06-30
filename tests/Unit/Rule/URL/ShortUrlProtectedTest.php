<?php

namespace Tests\Unit\Rule\URL;

use App\Rules\URL\ShortUrlProtected;
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
     * @param string $value
     * @return void
     * @dataProvider ShortUrlProtectedPass
     */
    public function testShortUrlProtectedPass($value)
    {
        $this->assertTrue($this->rule->passes('test', $value));
    }

    /**
     * @param string $value
     * @return void
     * @dataProvider ShortUrlProtectedFail
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
