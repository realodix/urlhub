<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('policy')]
class UserPolicyTest extends TestCase
{
    /**
     * Admin can access their own the page and other user pages.
     */
    #[PHPUnit\Test]
    public function viewAsAdmin(): void
    {
        $admin = $this->adminUser();

        $this->assertTrue($admin->can('view', $admin));
        $this->assertTrue($admin->can('view', new User));
    }

    /**
     * Non-admin can only access their own page.
     */
    #[PHPUnit\Test]
    public function viewAsBasicUser(): void
    {
        $user = $this->basicUser();

        $this->assertTrue($user->can('view', $user));
        $this->assertFalse($user->can('view', new User));
    }

    /**
     * Admin can change their own data and other user data.
     */
    #[PHPUnit\Test]
    public function updateAsAdmin(): void
    {
        $admin = $this->adminUser();

        $this->assertTrue($admin->can('update', $admin));
        $this->assertTrue($admin->can('update', new User));
    }

    /**
     * Non-admin can only change their own data.
     */
    #[PHPUnit\Test]
    public function updateAsBasicUser(): void
    {
        $user = $this->basicUser();

        $this->assertTrue($user->can('update', $user));
        $this->assertFalse($user->can('update', new User));
    }

    /**
     * Admin can change their own data and other user data.
     */
    #[PHPUnit\Test]
    public function updatePassAsAdmin(): void
    {
        $admin = $this->adminUser();

        $this->assertTrue($admin->can('updatePass', $admin));
        $this->assertTrue($admin->can('updatePass', new User));
    }

    /**
     * Non-admin can only change their own data.
     */
    #[PHPUnit\Test]
    public function updatePassAsBasicUser(): void
    {
        $user = $this->basicUser();

        $this->assertTrue($user->can('updatePass', $user));
        $this->assertFalse($user->can('updatePass', new User));
    }
}
