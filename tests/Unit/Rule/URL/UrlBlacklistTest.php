<?php

namespace Tests\Unit\Rule\URL;

use App\Rules\URL\DomainBlacklist;
use Tests\TestCase;

/**
 * @coversDefaultClass App\Rules\URL\DomainBlacklist
 */
class UrlBlacklistTest extends TestCase
{
    protected $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new DomainBlacklist();

        config()->set(
            'urlhub.domain_blacklist',
            ['github.com', 't.co']
        );
    }

    /**
     * @group u-rule
     * @covers ::passes
     * @dataProvider DomainBlacklistPass
     */
    public function testDomainBlacklistPass($value)
    {
        $this->assertTrue($this->rule->passes('test', $value));
    }

    /**
     * @group u-rule
     * @covers ::passes
     * @dataProvider DomainBlacklistFail
     */
    public function testDomainBlacklistFail($value)
    {
        $this->assertFalse($this->rule->passes('test', $value));
    }

    public function DomainBlacklistPass()
    {
        return [
            ['http://t.com/about'],
            ['https://t.com/about'],
            ['http://www.t.com/about'],
            ['https://www.t.com/about'],
        ];
    }

    public function DomainBlacklistFail()
    {
        return [
            ['https://github.com/laravel/laravel'],
            ['https://t.co/about'],
        ];
    }
}
