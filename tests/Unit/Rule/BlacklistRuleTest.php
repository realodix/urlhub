<?php

namespace Tests\Unit\Rule;

use App\Rules\Blacklist;
use Tests\TestCase;

class BlacklistRuleTest extends TestCase
{
    protected $rule;

    public function setUp():void
    {
        parent::setUp();

        $this->rule = new Blacklist();

        config()->set(
            'urlhub.blacklist',
            ['github.com', 't.co']
        );
    }

    /**
     * @return void
     */
    public function testBlacklistRulePass()
    {
        $this->assertTrue($this->rule->passes('test', 'http://laravel.com/docs'));
        $this->assertTrue($this->rule->passes('test', 'https://laravel.com/docs'));
        $this->assertTrue($this->rule->passes('test', 'http://www.laravel.com/docs'));
        $this->assertTrue($this->rule->passes('test', 'https://www.laravel.com/docs'));
        $this->assertTrue($this->rule->passes('test', 'http://t.com/about'));
        $this->assertTrue($this->rule->passes('test', 'https://t.com/about'));
        $this->assertTrue($this->rule->passes('test', 'http://www.t.com/about'));
        $this->assertTrue($this->rule->passes('test', 'https://www.t.com/about'));
    }

    /**
     * @return void
     */
    public function testBlacklistRuleFail()
    {
        $this->assertFalse($this->rule->passes('test', 'http://github.com/laravel/laravel'));
        $this->assertFalse($this->rule->passes('test', 'https://github.com/laravel/laravel'));
        $this->assertFalse($this->rule->passes('test', 'http://www.github.com/laravel/laravel'));
        $this->assertFalse($this->rule->passes('test', 'https://www.github.com/laravel/laravel'));
        $this->assertFalse($this->rule->passes('test', 'http://t.co/about'));
        $this->assertFalse($this->rule->passes('test', 'https://t.co/about'));
        $this->assertFalse($this->rule->passes('test', 'http://www.t.co/about'));
        $this->assertFalse($this->rule->passes('test', 'https://www.t.co/about'));
    }
}
