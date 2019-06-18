<?php

namespace Tests\Unit\Rule;

use App\Rules\URL\UrlBlacklist;
use Tests\TestCase;

class UrlBlacklistTest extends TestCase
{
    protected $rule;

    public function setUp():void
    {
        parent::setUp();

        $this->rule = new UrlBlacklist();

        config()->set(
            'urlhub.blacklist',
            ['github.com', 't.co']
        );
    }

    /**
     * @dataProvider UrlBlacklistPass
     * @return void
     */
    public function testUrlBlacklistPass($value)
    {
        $this->assertTrue($this->rule->passes('test', $value));
    }

    /**
     * @dataProvider UrlBlacklistFail
     * @return void
     */
    public function testUrlBlacklistFail($value)
    {
        $this->assertFalse($this->rule->passes('test', $value));
    }

    public function UrlBlacklistPass()
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
    public function UrlBlacklistFail()
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
