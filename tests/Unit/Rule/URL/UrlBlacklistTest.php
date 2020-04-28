<?php

namespace Tests\Unit\Rule\URL;

use App\Rules\URL\UrlBlacklist;
use Tests\TestCase;

/**
 * @coversDefaultClass App\Rules\URL\UrlBlacklist
 */
class UrlBlacklistTest extends TestCase
{
    protected $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new UrlBlacklist();

        config()->set(
            'urlhub.blacklist',
            ['github.com', 't.co']
        );
    }

    /**
     * @group u-rule
     * @covers ::passes
     * @dataProvider UrlBlacklistPass
     */
    public function testUrlBlacklistPass($value)
    {
        $this->assertTrue($this->rule->passes('test', $value));
    }

    /**
     * @group u-rule
     * @covers ::passes
     * @dataProvider UrlBlacklistFail
     */
    public function testUrlBlacklistFail($value)
    {
        $this->assertFalse($this->rule->passes('test', $value));
    }

    public function UrlBlacklistPass()
    {
        return [
            ['http://t.com/about'],
            ['https://t.com/about'],
            ['http://www.t.com/about'],
            ['https://www.t.com/about'],
        ];
    }

    public function UrlBlacklistFail()
    {
        return [
            ['https://github.com/laravel/laravel'],
            ['https://t.co/about'],
        ];
    }
}
