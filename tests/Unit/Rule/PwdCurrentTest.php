<?php

namespace Tests\Unit\Rule;

use App\Rules\PwdCurrent;
use Tests\TestCase;

class PwdCurrentTest extends TestCase
{
    /**
     * @var \App\Rules\PwdCurrent
     */
    protected $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new PwdCurrent;
        $this->actingAs($this->admin());
    }

    /**
     * @group u-rule
     */
    public function testPwdCurrentPass()
    {
        $this->assertTrue($this->rule->passes('test', $this->adminPass));
    }

    /**
     * @group u-rule
     */
    public function testPwdCurrentFail()
    {
        $this->assertFalse($this->rule->passes('test', 'wrong_password'));
    }
}
