<?php

namespace Tests\Unit\Rule;

use App\Rules\NotBlacklistedDomain;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\Support\Helper;
use Tests\TestCase;

class NotBlacklistedDomainTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['urlhub.domain_blacklist' => ['github.com', 't.co']]);
    }

    /**
     * @param mixed $value
     */
    #[Group('u-rule')]
    #[DataProvider('notBlacklistedDomainDataProvider')]
    public function testIsNotBlacklistedDomain($value): void
    {
        $val = Helper::validator(['foo' => $value], ['foo' => new NotBlacklistedDomain]);

        $this->assertTrue($val->passes());
        $this->assertSame([], $val->messages()->messages());
    }

    /**
     * @param mixed $value
     */
    #[Group('u-rule')]
    #[DataProvider('blacklistedDomainDataProvider')]
    public function testIsBlacklistedDomain($value): void
    {
        $val = Helper::validator(['foo' => $value], ['foo' => new NotBlacklistedDomain]);

        $this->assertTrue($val->fails());
        $this->assertSame([
            'foo' => [
                'Sorry, the URL you entered is on our internal blacklist. '.
                'It may have been used abusively in the past, or it may link to another URL redirection service.',
            ],
        ], $val->messages()->messages());
    }

    public static function notBlacklistedDomainDataProvider(): array
    {
        return [
            ['http://t.com/about'],
            ['https://t.com/about'],
            ['http://www.t.com/about'],
            ['https://www.t.com/about'],
        ];
    }

    public static function blacklistedDomainDataProvider(): array
    {
        return [
            ['https://github.com/laravel/laravel'],
            ['https://t.co/about'],
        ];
    }
}
