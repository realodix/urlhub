<?php

namespace Tests\Unit\Rule;

use App\Rules\BlacklistRule;
use Tests\TestCase;

class BlacklistRuleTest extends TestCase
{
    protected $rule;

    public function setUp():void
    {
        parent::setUp();

        $this->rule = new BlacklistRule();

        config()->set(
            'urlhub.blacklist',
            ['github.com', 't.co']
        );
    }

    /**
     * @dataProvider blacklistRulePass
     * @return void
     */
    public function testBlacklistRulePass($value)
    {
        $this->assertTrue($this->rule->passes('test', $value));
    }

    /**
     * @dataProvider blacklistRuleFail
     * @return void
     */
    public function testBlacklistRuleFail($value)
    {
        $this->assertFalse($this->rule->passes('test', $value));
    }

    public function blacklistRulePass()
    {
        return [
            ['http://laravel.com/docs'],
            ['https://laravel.com/docs'],
            ['http://www.laravel.com/docs'],
            ['https://www.laravel.com/docs'],
            ['http://t.com/about'],
            ['https://t.com/about'],
            ['http://www.t.com/about'],
            ['https://www.t.com/about'],
        ];
    }

    /**
     * @return void
     */
    public function blacklistRuleFail()
    {
        return [
            ['http://github.com/laravel/laravel'],
            ['https://github.com/laravel/laravel'],
            ['http://www.github.com/laravel/laravel'],
            ['https://www.github.com/laravel/laravel'],
            ['http://t.co/about'],
            ['https://t.co/about'],
            ['http://www.t.co/about'],
            ['https://www.t.co/about'],
        ];
    }
}
