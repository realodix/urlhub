<?php

namespace Tests\Unit\Rule;

use App\Rules\Url\DomainBlacklist;
use App\Rules\Url\KeywordBlacklist;
use Tests\Support\Helper;
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
        $val = Helper::validator(['foo' => $value], ['foo' => new DomainBlacklist]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
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
        $val = Helper::validator(['foo' => $value], ['foo' => new DomainBlacklist]);

        $this->assertTrue($val->fails());
        $this->assertSame([
            'foo' => [
                'Sorry, the URL you entered is on our internal blacklist. '.
                'It may have been used abusively in the past, or it may link to another URL redirection service.',
            ],
        ], $val->messages()->messages());
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
        $val = Helper::validator(['foo' => $value], ['foo' => new KeywordBlacklist]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    /**
     * @test
     * @group u-rule
     * @dataProvider customKeywordContainsRegisteredRouteWillFailDataProvider
     *
     * @param array $value
     */
    public function customKeywordContainsRegisteredRouteWillFail($value)
    {
        $val = Helper::validator(['foo' => $value], ['foo' => new KeywordBlacklist]);

        $this->assertTrue($val->fails());
        $this->assertSame(['foo' => ['Not available.']], $val->messages()->messages());
    }

    public function customKeywordContainsReservedKeywordWillFail()
    {
        $value = 'css';
        config(['urlhub.reserved_keyword' => $value]);

        $val = Helper::validator(['foo' => $value], ['foo' => new KeywordBlacklist]);

        $this->assertTrue($val->fails());
        $this->assertSame(['foo' => ['Not available.']], $val->messages()->messages());
    }

    public function customKeywordBlacklistPassDataProvider()
    {
        return [
            ['hello'],
            ['laravel'],
        ];
    }

    public function customKeywordContainsRegisteredRouteWillFailDataProvider()
    {
        return [
            ['login'],
            ['register'],
        ];
    }
}
