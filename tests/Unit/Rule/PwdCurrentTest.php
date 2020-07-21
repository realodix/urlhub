<?php

namespace Tests\Unit\Rule;

use App\Rules\PwdCurrent;
use Tests\TestCase;

class PwdCurrentTest extends TestCase
{
    protected $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new PwdCurrent();
        $this->loginAsAdmin();
    }

    /**
     * @group u-rule
     */
    public function testPwdCurrentPass()
    {
        $this->assertTrue($this->rule->passes('test', $this->adminPassword()));
    }

    /**
     * @group u-rule
     */
    public function testPwdCurrentFail()
    {
        $this->assertFalse($this->rule->passes('test', 'wrong_password'));
    }
}
