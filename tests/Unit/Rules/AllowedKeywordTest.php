<?php

namespace Tests\Unit\Rules;

use App\Rules\AllowedKeyword;
use App\Services\KeyGeneratorService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('validation-rule')]
class AllowedKeywordTest extends TestCase
{
    #[PHPUnit\DataProvider('allowedKeywordDataProvider')]
    #[PHPUnit\Test]
    public function allowedKeywordPasses($value): void
    {
        $val = validator(['foo' => $value], ['foo' => new AllowedKeyword]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    public static function allowedKeywordDataProvider(): array
    {
        return [
            ['hello'],
            ['laravel'],
        ];
    }

    /**
     * When custom keyword is registered route, it should not be available.
     */
    #[PHPUnit\DataProvider('registeredRouteDataProvider')]
    #[PHPUnit\Test]
    public function keywordIsRegisteredRoute($value): void
    {
        $val = validator(['foo' => $value], ['foo' => new AllowedKeyword]);

        $this->assertTrue($val->fails());
        $this->assertSame(['foo' => ['Not available.']], $val->messages()->messages());
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
     * When custom keyword is reserved keyword, it should not be available.
     */
    #[PHPUnit\Test]
    public function keywordIsReservedKeyword(): void
    {
        $value = KeyGeneratorService::RESERVED_KEYWORD[0];
        $val = validator(['foo' => $value], ['foo' => new AllowedKeyword]);

        $this->assertTrue($val->fails());
        $this->assertSame(['foo' => ['Not available.']], $val->messages()->messages());
    }
}
