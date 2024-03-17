<?php

namespace Tests\Unit\Rule;

use App\Rules\StrAlphaUnderscore;
use PHPUnit\Framework\Attributes\{Group, Test};
use Tests\Support\Helper;
use Tests\TestCase;

class StrAlphaUnderscoreTest extends TestCase
{
    #[Test]
    #[Group('u-rule')]
    public function strAlphaUnderscorePass(): void
    {
        $val = Helper::validator(['foo' => 'foo_bar'], ['foo' => new StrAlphaUnderscore]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    #[Test]
    #[Group('u-rule')]
    public function strAlphaUnderscoreFail(): void
    {
        $val = Helper::validator(['foo' => 'foo-bar'], ['foo' => new StrAlphaUnderscore]);
        $this->assertTrue($val->fails());

        $val = Helper::validator(['foo' => 'fo0_b@r'], ['foo' => new StrAlphaUnderscore]);
        $this->assertTrue($val->fails());

        $this->assertSame([
            'foo' => ['The foo may only contain letters, numbers and underscores.'],
        ], $val->messages()->messages());
    }
}
