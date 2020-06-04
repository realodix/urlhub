<?php

namespace Tests\Unit\Rule;

use App\Rules\StrLowercase;
use Tests\TestCase;

class StrLowercaseTest extends TestCase
{
    protected $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new StrLowercase();
    }

    /**
     * @group u-rule
     */
    public function testStrLowercasePass()
    {
        $this->assertTrue($this->rule->passes('test', 'foo'));
    }

    /**
     * @group u-rule
     */
    public function testStrLowercaseFail()
    {
        $this->assertFalse($this->rule->passes('test', 'Foo'));
    }
}
