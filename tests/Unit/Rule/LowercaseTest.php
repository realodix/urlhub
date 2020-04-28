<?php

namespace Tests\Unit\Rule;

use App\Rules\Lowercase;
use Tests\TestCase;

class LowercaseTest extends TestCase
{
    protected $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new Lowercase();
    }

    /**
     * @group u-rule
     */
    public function testLowercasePass()
    {
        $this->assertTrue($this->rule->passes('test', 'foo'));
    }

    /**
     * @group u-rule
     */
    public function testLowercaseFail()
    {
        $this->assertFalse($this->rule->passes('test', 'Foo'));
    }
}
