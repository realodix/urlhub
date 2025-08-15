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
    #[PHPUnit\Test]
    public function name_Pass($value): void
    {
        $val = validator(['name' => $value], ['name' => UserRules::name()]);

        $this->assertTrue($val->passes());
    }

    #[PHPUnit\TestWith(['foÖ123'])] // non-ascii
    #[PHPUnit\TestWith(['foo_123'])]
    #[PHPUnit\TestWith(['foo-123'])]
    #[PHPUnit\Test]
    public function name_Fail($value): void
    {
        $val = validator(['name' => $value], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    #[PHPUnit\Test]
    public function name_Fail_Lowercase(): void
    {
        $val = validator(['name' => 'Laravel'], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    #[PHPUnit\Test]
    public function name_Fail_Max(): void
    {
        $val = validator(['name' => str_repeat('a', 21)], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    #[PHPUnit\Test]
    public function name_Fail_Min(): void
    {
        $val = validator(['name' => str_repeat('a', 3)], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    #[PHPUnit\Test]
    public function name_Fail_Unique(): void
    {
        $user = User::factory()->create(['name' => 'test']);

        $val = validator(['name' => $user->name], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    #[PHPUnit\TestWith(['guest'])]
    #[PHPUnit\TestWith(['guests'])]
    #[PHPUnit\Test]
    public function name_Fail_Blacklist_Guest($value): void
    {
        $val = validator(['name' => $value], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    #[PHPUnit\Test]
    public function name_Fail_Blacklist_Config(): void
    {
        config(['urlhub.blacklist_username' => ['laravel']]);
        $val = validator(['name' => 'laravel'], ['name' => UserRules::name()]);
        $this->assertTrue($val->fails());
    }

    #[PHPUnit\TestWith(['foo'])]
    #[PHPUnit\Test]
    public function email_Fail($value): void
    {
        $val = validator(['email' => $value], ['email' => UserRules::email()]);
        $this->assertTrue($val->fails());
    }

    #[PHPUnit\Test]
    public function email_Fail_Unique(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $val = validator(['email' => $user->email], ['email' => UserRules::email()]);
        $this->assertTrue($val->fails());
    }
}
