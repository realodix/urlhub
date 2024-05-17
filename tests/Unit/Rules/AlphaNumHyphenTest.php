<?php

namespace Tests\Unit\Rules;

use App\Rules\AlphaNumHyphen;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\Support\Helper;
use Tests\TestCase;

#[PHPUnit\Group('validation-rule')]
class AlphaNumHyphenTest extends TestCase
{
    #[PHPUnit\Test]
    public function AlphaNumHyphenPass(): void
    {
        $val = Helper::validator(['foo' => 'foo-bar'], ['foo' => new AlphaNumHyphen]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    #[PHPUnit\Test]
    public function AlphaNumHyphenFail(): void
    {
        $val = Helper::validator(['foo' => 'foo_bar'], ['foo' => new AlphaNumHyphen]);
        $this->assertTrue($val->fails());

        $val = Helper::validator(['foo' => 'fo0-b@r'], ['foo' => new AlphaNumHyphen]);
        $this->assertTrue($val->fails());

        $this->assertSame([
            'foo' => ['The foo may only contain letters, numbers and hyphens.'],
        ], $val->messages()->messages());
    }
}
