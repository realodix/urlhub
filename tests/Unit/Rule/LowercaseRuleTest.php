<?php

namespace Tests\Unit\Rule;

use App\Rules\Lowercase;
use Tests\TestCase;

class LowercaseRuleTest extends TestCase
{
    protected $rule;

    public function setUp()
    {
        parent::setUp();

        $this->rule = new Lowercase();
    }
}
