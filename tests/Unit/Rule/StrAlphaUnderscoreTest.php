<?php

namespace Tests\Unit\Rule;

use App\Rules\StrAlphaUnderscore;
use Tests\TestCase;

class StrAlphaUnderscoreTest extends TestCase
{
    use Helper;

    /**
     * @test
     * @group u-rule
     */
    public function strAlphaUnderscorePass()
    {
        $val = $this->validator(['foo' => 'foo_bar'], ['foo' => new StrAlphaUnderscore]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    /**
     * @test
     * @group u-rule
     */
    public function strAlphaUnderscoreFail()
    {
        $val = $this->validator(['foo' => 'foo-bar'], ['foo' => new StrAlphaUnderscore]);
        $this->assertTrue($val->fails());

        $val = $this->validator(['foo' => 'fo0_b@r'], ['foo' => new StrAlphaUnderscore]);
        $this->assertTrue($val->fails());

        $this->assertSame([
            'foo' => [
                'The foo may only contain letters, numbers and underscores.',
            ],
        ], $val->messages()->messages());
    }
}
