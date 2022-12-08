<?php

namespace Tests\Unit\Rule;

use App\Rules\StrAlphaUnderscore;
use Illuminate\Validation\Validator;
use Tests\TestCase;

class StrAlphaUnderscoreTest extends TestCase
{
    /**
     * @test
     * @group u-rule
     */
    public function strAlphaUnderscorePass()
    {
        $trans = $this->getIlluminateArrayTranslator();
        $validator = new Validator($trans, ['foo' => 'foo_bar'], ['foo' => new StrAlphaUnderscore]);

        $this->assertTrue($validator->passes());
        $this->assertSame([], $validator->messages()->messages());
    }

    /**
     * @test
     * @group u-rule
     */
    public function strAlphaUnderscoreFail()
    {
        $trans = $this->getIlluminateArrayTranslator();
        $validator = new Validator($trans, ['foo' => 'foo-bar'], ['foo' => new StrAlphaUnderscore]);
        $this->assertTrue($validator->fails());

        $validator = new Validator($trans, ['foo' => 'fo0_b@r'], ['foo' => new StrAlphaUnderscore]);
        $this->assertTrue($validator->fails());

        $this->assertSame([
            'foo' => [
                'The foo may only contain letters, numbers and underscores.',
            ],
        ], $validator->messages()->messages());
    }
}
