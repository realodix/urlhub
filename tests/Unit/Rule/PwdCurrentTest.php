<?php

namespace Tests\Unit\Rule;

use App\Rules\PwdCurrent;
use Tests\Support\Helper;
use Tests\TestCase;

class PwdCurrentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->adminUser());
    }

    /**
     * @group u-rule
     */
    public function testPwdCurrentPass()
    {
        $val = Helper::validator(['foo' => $this->adminPass], ['foo' => new PwdCurrent]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    /**
     * @group u-rule
     */
    public function testPwdCurrentFail()
    {
        $val = Helper::validator(['foo' => 'bar'], ['foo' => new PwdCurrent]);

        $this->assertTrue($val->fails());
        $this->assertSame([
            'foo' => [
                'The password you entered does not match your password. Please try again.',
            ],
        ], $val->messages()->messages());
    }
}
