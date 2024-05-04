<?php

namespace Tests\Unit\Rule;

use App\Rules\NotBlacklistedKeyword;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\Support\Helper;
use Tests\TestCase;

class NotBlacklistedKeywordTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['urlhub.domain_blacklist' => ['github.com', 't.co']]);
    }

    #[Group('u-rule')]
    public static function customKeywordIsNotBlacklistedDataProvider(): array
    {
        return [
            ['hello'],
            ['laravel'],
        ];
    }

    /**
     * @param string $value
     */
    #[Group('u-rule')]
    #[DataProvider('customKeywordIsNotBlacklistedDataProvider')]
    public function testCutomKeywordIsNotBlacklisted($value): void
    {
        $val = Helper::validator(['foo' => $value], ['foo' => new NotBlacklistedKeyword]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    public static function registeredRouteDataProvider(): array
    {
        return [
            ['login'],
            ['register'],
        ];
    }

    /**
     * When custom keyword is registered route, it should not be available.
     *
     * @param array $value
     */
    #[Group('u-rule')]
    #[DataProvider('registeredRouteDataProvider')]
    public function testCustomKeywordIsRegisteredRoute($value): void
    {
        $val = Helper::validator(['foo' => $value], ['foo' => new NotBlacklistedKeyword]);

        $this->assertTrue($val->fails());
        $this->assertSame(['foo' => ['Not available.']], $val->messages()->messages());
    }

    /**
     * When custom keyword is reserved keyword, it should not be available.
     */
    #[Group('u-rule')]
    public function testCustomKeywordIsReservedKeyword(): void
    {
        $value = 'css';
        config(['urlhub.reserved_keyword' => [$value]]);

        $val = Helper::validator(['foo' => $value], ['foo' => new NotBlacklistedKeyword]);

        $this->assertTrue($val->fails());
        $this->assertSame(['foo' => ['Not available.']], $val->messages()->messages());
    }
}
