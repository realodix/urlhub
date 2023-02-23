<?php

namespace Tests\Unit\Rule;

use App\Models\User;
use App\Rules\PwdCurrent;
use Tests\Support\Helper;
use Tests\TestCase;

class PwdCurrentTest extends TestCase
{
    protected User $user;

    protected static string $password = 'old-password';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => bcrypt(self::$password),
        ]);

        $this->actingAs($this->user);
    }

    /**
     * @group u-rule
     */
    public function testPwdCurrentPass(): void
    {
        $val = Helper::validator(['foo' => self::$password], ['foo' => new PwdCurrent]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    /**
     * @group u-rule
     */
    public function testPwdCurrentFail(): void
    {
        $val = Helper::validator(['foo' => 'bar'], ['foo' => new PwdCurrent]);

        $this->assertTrue($val->fails());
        $this->assertSame([
            'foo' => ['The password you entered does not match your password. Please try again.'],
        ], $val->messages()->messages());
    }
}
