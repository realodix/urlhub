<?php

namespace Tests\Unit\Rule;

use App\Rules\StrAlphaUnderscore;
use App\Rules\StrLowercase;
use Tests\TestCase;

class StringTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     * @group u-rule
     */
    public function strAlphaUnderscoret()
    {
        $rule = new StrAlphaUnderscore();

        $this->assertTrue($rule->passes('test', 'foo_BAR'));
        $this->assertFalse($rule->passes('test', 'fo0-BAR'));
    }

    /**
     * @test
     * @group u-rule
     */
    public function strLowercase()
    {
        $rule = new StrLowercase();

        $this->assertTrue($rule->passes('test', 'foo'));
        $this->assertFalse($rule->passes('test', 'Foo'));
    }
}
