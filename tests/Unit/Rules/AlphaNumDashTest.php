<?php

namespace Tests\Unit\Rules;

use App\Rules\AlphaNumDash;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('validation-rule')]
class AlphaNumDashTest extends TestCase
{
    #[PHPUnit\TestWith(['foo-B4r'])]
    #[PHPUnit\TestWith(['123'])] // numeric string
    public function testAlphaNumDashPass($value): void
    {
        $val = validator(['foo' => $value], ['foo' => new AlphaNumDash]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    #[PHPUnit\TestWith(['foo-B4r '])]
    #[PHPUnit\TestWith(['foo_B4r'])]
    #[PHPUnit\TestWith([null])]
    public function testAlphaNumDashFail($value): void
    {
        $val = validator(['foo' => $value], ['foo' => new AlphaNumDash]);

        $this->assertTrue($val->fails());
        $this->assertSame([
            'foo' => ['The foo may only contain letters, numbers and dashes.'],
        ], $val->messages()->messages());
    }
}
