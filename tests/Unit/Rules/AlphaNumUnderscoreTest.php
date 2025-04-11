<?php

namespace Tests\Unit\Rules;

use App\Rules\AlphaNumUnderscore;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('validation-rule')]
class AlphaNumUnderscoreTest extends TestCase
{
    #[PHPUnit\TestWith(['foo_B4r'])]
    public function testAlphaNumUnderscorePass($value): void
    {
        $val = validator(['foo' => $value], ['foo' => new AlphaNumUnderscore]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    #[PHPUnit\TestWith(['foo_B4r '])]
    #[PHPUnit\TestWith(['foo-B4r'])]
    #[PHPUnit\TestWith([null])]
    public function testAlphaNumUnderscoreFail($value): void
    {
        $val = validator(['foo' => $value], ['foo' => new AlphaNumUnderscore]);

        $this->assertTrue($val->fails());
        $this->assertSame([
            'foo' => ['The foo may only contain letters, numbers and underscores.'],
        ], $val->messages()->messages());
    }
}
