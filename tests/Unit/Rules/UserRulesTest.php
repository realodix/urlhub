<?php

namespace Tests\Unit\Rules;

use App\Models\User;
use App\Rules\UserRules;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('validation-rule')]
class UserRulesTest extends TestCase
{
    #[PHPUnit\TestWith(['foo123'])]
    public function testNamePass($value): void
    {
        $val = validator(['name' => $value], ['name' => UserRules::name()]);

        $this->assertTrue($val->passes());
    }

    #[PHPUnit\TestWith(['foÖ123'])] // non-ascii
    #[PHPUnit\TestWith(['foo_123'])]
    #[PHPUnit\TestWith(['foo-123'])]
    public function testNameFail($value): void
    {
        $val = validator(['name' => $value], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    #[PHPUnit\TestWith(['guest'])]
    #[PHPUnit\TestWith(['guests'])]
    public function testNameFail_Blacklist_Guest($value): void
    {
        $val = validator(['name' => $value], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    public function testNameFail_Blacklist_Config(): void
    {
        config(['urlhub.username_blacklist' => ['laravel']]);
        $val = validator(['name' => 'laravel'], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    public function testNameMaxFail(): void
    {
        $val = validator(['name' => str_repeat('a', 21)], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    public function testNameMinFail(): void
    {
        $val = validator(['name' => str_repeat('a', 3)], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    public function testNameUniqueFail(): void
    {
        $user = User::factory()->create(['name' => 'test']);

        $val = validator(['name' => $user->name], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    #[PHPUnit\TestWith(['foo'])]
    public function testEmailFail($value): void
    {
        $val = validator(['email' => $value], ['email' => UserRules::email()]);
        $this->assertTrue($val->fails());
    }

    public function testEmailUniqueFail(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $val = validator(['email' => $user->email], ['email' => UserRules::email()]);
        $this->assertTrue($val->fails());
    }
}
