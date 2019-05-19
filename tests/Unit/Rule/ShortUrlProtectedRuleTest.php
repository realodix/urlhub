<?php

namespace Tests\Unit\Rule;

use App\Rules\ShortUrlProtected;
use Illuminate\Validation\Rule;
use Tests\TestCase;

class ShortUrlProtectedRuleTest extends TestCase
{
    protected $rule;

    public function setUp():void
    {
        parent::setUp();

        $this->rule = new ShortUrlProtected();
    }
}
