<?php

namespace Tests\Unit\Rule;

use App\Rules\AlphaNumHyphen;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\Helper;
use Tests\TestCase;

class AlphaNumHyphenTest extends TestCase
{
    #[Test]
    #[Group('u-rule')]
    public function AlphaNumHyphenPass(): void
    {
        $val = Helper::validator(['foo' => 'foo-bar'], ['foo' => new AlphaNumHyphen]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    #[Test]
    #[Group('u-rule')]
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
