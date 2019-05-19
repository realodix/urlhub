<?php

namespace Tests\Unit\Rule;

use App\Rules\ShortUrlProtected;
use Tests\TestCase;

class ShortUrlProtectedRuleTest extends TestCase
{
    protected $rule;

    public function setUp():void
    {
        parent::setUp();

        $this->rule = new ShortUrlProtected();
    }

    /**
     * @dataProvider shortUrlProtectedRulePass
     * @param string $value
     * @return void
     */
    public function testShortUrlProtectedRulePass($value)
    {
        $this->assertTrue($this->rule->passes('test', $value));
    }

    /**
     * @dataProvider shortUrlProtectedRuleFail
     * @param string $value
     * @return void
     */
    public function testShortUrlProtectedRuleFail($value)
    {
        $this->assertFalse($this->rule->passes('test', $value));
    }

    public function shortUrlProtectedRulePass()
    {
        return [
            ['hello'],
            ['laravel'],
        ];
    }

    public function shortUrlProtectedRuleFail()
    {
        return [
            ['login'],
            ['admin'],
        ];
    }
}
