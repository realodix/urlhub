<?php

namespace Tests\Unit\Rules;

use App\Rules\NotBlacklistedKeyword;
use App\Services\KeyGeneratorService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('validation-rule')]
class NotBlacklistedKeywordTest extends TestCase
{
    public static function customKeywordIsNotBlacklistedDataProvider(): array
    {
        return [
            ['hello'],
            ['laravel'],
        ];
    }

    #[PHPUnit\DataProvider('customKeywordIsNotBlacklistedDataProvider')]
    public function testCutomKeywordIsNotBlacklisted($value): void
    {
        $val = validator(['foo' => $value], ['foo' => new NotBlacklistedKeyword]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    public static function registeredRouteDataProvider(): array
    {
        return [
            ['login'],
            ['register'],

            // in public folder
            ['svg'],   // folder
            ['build'], // vite folder
        ];
    }

    /**
     * When custom keyword is registered route, it should not be available.
     */
    #[PHPUnit\DataProvider('registeredRouteDataProvider')]
    public function testCustomKeywordIsRegisteredRoute($value): void
    {
        $val = validator(['foo' => $value], ['foo' => new NotBlacklistedKeyword]);

        $this->assertTrue($val->fails());
        $this->assertSame(['foo' => ['Not available.']], $val->messages()->messages());
    }

    /**
     * When custom keyword is reserved keyword, it should not be available.
     */
    public function testCustomKeywordIsReservedKeyword(): void
    {
        $value = KeyGeneratorService::RESERVED_KEYWORD[0];
        $val = validator(['foo' => $value], ['foo' => new NotBlacklistedKeyword]);

        $this->assertTrue($val->fails());
        $this->assertSame(['foo' => ['Not available.']], $val->messages()->messages());
    }
}
