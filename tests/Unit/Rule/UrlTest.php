<?php

namespace Tests\Unit\Rule;

use App\Rules\NotBlacklistedKeyword;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
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
     * @param string $value
     */
    #[Test]
    #[Group('f-user')]
    #[DataProvider('customKeywordBlacklistPassDataProvider')]
    public function customKeywordBlacklistPass($value): void
    {
        $val = Helper::validator(['foo' => $value], ['foo' => new NotBlacklistedKeyword]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    /**
     * @param array $value
     */
    #[Test]
    #[Group('f-user')]
    #[DataProvider('customKeywordContainsRegisteredRouteWillFailDataProvider')]
    public function customKeywordContainsRegisteredRouteWillFail($value): void
    {
        $val = Helper::validator(['foo' => $value], ['foo' => new NotBlacklistedKeyword]);

        $this->assertTrue($val->fails());
        $this->assertSame(['foo' => ['Not available.']], $val->messages()->messages());
    }

    public function customKeywordContainsReservedKeywordWillFail(): void
    {
        $value = 'css';
        config(['urlhub.reserved_keyword' => $value]);

        $val = Helper::validator(['foo' => $value], ['foo' => new NotBlacklistedKeyword]);

        $this->assertTrue($val->fails());
        $this->assertSame(['foo' => ['Not available.']], $val->messages()->messages());
    }

    public static function customKeywordBlacklistPassDataProvider(): array
    {
        return [
            ['hello'],
            ['laravel'],
        ];
    }

    public static function customKeywordContainsRegisteredRouteWillFailDataProvider(): array
    {
        return [
            ['login'],
            ['register'],
        ];
    }
}
