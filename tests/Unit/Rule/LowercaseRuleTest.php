<?php

namespace Tests\Unit\Rule;

use App\Rules\Lowercase;
use Tests\TestCase;

class LowercaseRuleTest extends TestCase
{
    protected $rule;

    public function setUp():void
    {
        parent::setUp();

        $this->rule = new Lowercase();
    }

    /**
     * @return void
     */
    public function testLowercaseRulePass()
    {
        $lowercase = 'abc';

        $this->assertTrue($this->rule->passes('test', $lowercase));
    }

    /**
     * @return void
     */
    public function testLowercaseRuleFail()
    {
        $uppercase = 'ABC';

        $this->assertFalse($this->rule->passes('test', $uppercase));
    }
}
