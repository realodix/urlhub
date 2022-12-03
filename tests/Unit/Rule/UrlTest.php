<?php

namespace Tests\Unit\Rule;

use App\Rules\Url\DomainBlacklist;
use App\Rules\Url\KeywordBlacklist;
use Tests\TestCase;

class UrlTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['urlhub.domain_blacklist' => ['github.com', 't.co']]);
    }

    /**
     * @test
     * @group u-rule
     * @dataProvider domainBlacklistPassDataProvider
     *
     * @param mixed $value
     */
    public function domainBlacklistPass($value)
    {
        $rule = new DomainBlacklist;
        $this->assertTrue($rule->passes('test', $value));
    }

    /**
     * @test
     * @group u-rule
     * @dataProvider domainBlacklistFailDataProvider
     *
     * @param mixed $value
     */
    public function domainBlacklistFail($value)
    {
        $rule = new DomainBlacklist;
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
     * @dataProvider customKeywordBlacklistPassDataProvider
     *
     * @param string $value
     */
    public function customKeywordBlacklistPass($value)
    {
        $rule = new KeywordBlacklist;
        $this->assertTrue($rule->passes('test', $value));
    }

    /**
     * @test
     * @group u-rule
     * @dataProvider customKeywordBlacklistFailDataProvider
     *
     * @param array $value
     */
    public function customKeywordBlacklistFail($value)
    {
        $rule = new KeywordBlacklist;
        $this->assertFalse($rule->passes('test', $value));
    }

    public function customKeywordBlacklistPassDataProvider()
    {
        return [
            ['hello'],
            ['laravel'],
        ];
    }

    public function customKeywordBlacklistFailDataProvider()
    {
        return [
            ['login'],
            ['register'],
            ['css'], // urlhub.reserved_keyword
        ];
    }
}
