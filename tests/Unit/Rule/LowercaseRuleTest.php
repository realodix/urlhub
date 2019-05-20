<?php

namespace Tests\Unit\Rule;

use App\Rules\LowercaseRule;
use Tests\TestCase;

class LowercaseRuleTest extends TestCase
{
    protected $rule;

    public function setUp():void
    {
        parent::setUp();

        $this->rule = new LowercaseRule();
    }

    /**
     * @return void
     */
    public function testLowercaseRulePass()
    {
        $this->assertTrue($this->rule->passes('test', 'abc'));
        $this->assertTrue($this->rule->passes('test', '1bc'));
    }

    /**
     * @return void
     */
    public function testLowercaseRuleFail()
    {
        $this->assertFalse($this->rule->passes('test', 'ABC'));
        $this->assertFalse($this->rule->passes('test', '1BC'));
        $this->assertFalse($this->rule->passes('test', '1bC'));
    }
}
