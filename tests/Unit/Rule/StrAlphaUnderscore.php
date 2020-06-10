<?php

namespace Tests\Unit\Rule;

use App\Rules\StrAlphaUnderscore;
use Tests\TestCase;

class StrAlphaUnderscore extends TestCase
{
    protected $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new self();
        $this->loginAsAdmin();
    }

    /**
     * @group u-rule
     */
    public function testStrAlphaUnderscoretPass()
    {
        $this->assertTrue($this->rule->passes('test', 'foo_BAR'));
    }

    /**
     * @group u-rule
     */
    public function testStrAlphaUnderscoreFail()
    {
        $this->assertTrue($this->rule->passes('test', 'foo-BAR'));
    }
}
