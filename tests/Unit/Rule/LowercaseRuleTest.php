<?php

namespace Tests\Unit\Rule;

use App\Rules\Lowercase;
use Illuminate\Validation\Rule;
use Tests\TestCase;

class LowercaseRuleTest extends TestCase
{
    protected $rule;

    public function setUp():void
    {
        parent::setUp();

        $this->rule = new Lowercase();
    }
}
