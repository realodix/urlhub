<?php

namespace Tests\Unit\Rule;

use App\Rules\URL\DomainBlacklist;
use App\Rules\URL\KeywordBlacklist;
use Tests\TestCase;

class UrlTest extends TestCase
{
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
     * @test
     * @group u-rule
     * @covers ::passes
     * @dataProvider domainBlacklistPassDataProvider
     */
    public function domainBlacklistPass($value)
    {
        $rule = new DomainBlacklist();
        $this->assertTrue($rule->passes('test', $value));
    }

    /**
     * @test
     * @group u-rule
     * @covers ::passes
     * @dataProvider domainBlacklistFailDataProvider
     */
    public function domainBlacklistFail($value)
    {
        $rule = new DomainBlacklist();
        $this->assertFalse($rule->passes('test', $value));
    }

    public function domainBlacklistPassDataProvider()
    {
        return [
            ['http://t.com/about'],
            ['https://t.com/about'],
            ['http://www.t.com/about'],
            ['https://www.t.com/about'],
        ];
    }

    public function domainBlacklistFailDataProvider()
    {
        return [
            ['https://github.com/laravel/laravel'],
            ['https://t.co/about'],
        ];
    }

    /**
     * @test
     * @group u-rule
     * @dataProvider keywordBlacklistPassDataProvider
     * @param string $value
     */
    public function keywordBlacklistPass($value)
    {
        $rule = new KeywordBlacklist();
        $this->assertTrue($rule->passes('test', $value));
    }

    /**
     * @test
     * @group u-rule
     * @dataProvider keywordBlacklistFailDataProvider
     * @param string $value
     */
    public function keywordBlacklistFail($value)
    {
        $rule = new KeywordBlacklist();
        $this->assertFalse($rule->passes('test', $value));
    }

    public function keywordBlacklistPassDataProvider()
    {
        return [
            ['hello'],
            ['laravel'],
        ];
    }

    public function keywordBlacklistFailDataProvider()
    {
        return [
            ['login'],
            ['register'],
            ['css'], // urlhub.reserved_keyword
        ];
    }
}
