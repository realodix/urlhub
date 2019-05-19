<?php

namespace Tests\Unit\Rule;

use App\Rules\Blacklist;
use Illuminate\Validation\Rule;
use Tests\TestCase;

class BlacklistRuleTest extends TestCase
{
    protected $rule;

    public function setUp()
    {
        parent::setUp();

        $this->rule = new Blacklist();
    }
}
