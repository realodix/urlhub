<?php

namespace Tests\Unit\Rule;

use App\Rules\PwdCurrent;
use Illuminate\Validation\Validator;
use Tests\TestCase;

class PwdCurrentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->admin());
    }

    /**
     * @group u-rule
     */
    public function testPwdCurrentPass()
    {
        $trans = $this->getIlluminateArrayTranslator();
        $validator = new Validator($trans, ['foo' => $this->adminPass], ['foo' => new PwdCurrent]);

        $this->assertTrue($validator->passes());
        $this->assertSame([], $validator->messages()->messages());
    }

    /**
     * @group u-rule
     */
    public function testPwdCurrentFail()
    {
        $trans = $this->getIlluminateArrayTranslator();
        $validator = new Validator($trans, ['foo' => 'bar'], ['foo' => new PwdCurrent]);

        $this->assertTrue($validator->fails());
        $this->assertSame([
            'foo' => [
                'The password you entered does not match your password. Please try again.',
            ],
        ], $validator->messages()->messages());
    }
}
