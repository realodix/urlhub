<?php

namespace Tests\Unit\Rules;

use App\Rules\AllowedDomain;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('validation-rule')]
class AllowedDomainTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['urlhub.blacklist_domain' => ['github.com', 't.co']]);
    }

    #[PHPUnit\DataProvider('allowedDomainDataProvider')]
    #[PHPUnit\Test]
    public function allowedDomainPasses($value): void
    {
        $val = validator(['foo' => $value], ['foo' => new AllowedDomain]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    #[PHPUnit\DataProvider('disallowedDomainDataProvider')]
    #[PHPUnit\Test]
    public function disallowedDomainFails($value): void
    {
        $val = validator(['foo' => $value], ['foo' => new AllowedDomain]);

        $this->assertTrue($val->fails());
        $this->assertSame([
            'foo' => [
                'Sorry, the URL you entered is on our internal blacklist. '
                .'It may have been used abusively in the past, or it may link to another URL redirection service.',
            ],
        ], $val->messages()->messages());
    }

    public static function allowedDomainDataProvider(): array
    {
        return [
            ['http://t.com/about'],
            ['https://t.com/about'],
            ['http://www.t.com/about'],
            ['https://www.t.com/about'],
        ];
    }

    public static function disallowedDomainDataProvider(): array
    {
        return [
            ['https://github.com/laravel/laravel'],
            ['https://docs.github.com/en'],
            ['https://t.co/about'],
        ];
    }
}
