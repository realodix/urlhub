<?php

namespace Tests\Unit\Rule\URL;

use App\Rules\URL\ShortUrlProtected;
use Tests\TestCase;

class ShortUrlProtectedTest extends TestCase
{
    protected $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new ShortUrlProtected();
    }

    /**
     * @group u-rule
     * @dataProvider ShortUrlProtectedPass
     * @param string $value
     */
    public function testShortUrlProtectedPass($value)
    {
        $this->assertTrue($this->rule->passes('test', $value));
    }

    /**
     * @group u-rule
     * @dataProvider ShortUrlProtectedFail
     * @param string $value
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
            ['css'], // urlhub.keyword_blacklist
        ];
    }
}
