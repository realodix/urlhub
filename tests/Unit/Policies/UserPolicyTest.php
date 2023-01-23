<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    /**
     * Admin can access their own the page and other user pages.
     *
     * @test
     * @group u-policy
     */
    public function viewAsAdmin()
    {
        $admin = $this->adminUser();

        $this->assertTrue($admin->can('view', $admin));
        $this->assertTrue($admin->can('view', new User));
    }

    /**
     * Non-admin can only access their own page.
     *
     * @test
     * @group u-policy
     */
    public function viewAsNormalUser()
    {
        $user = $this->normalUser();

        $this->assertTrue($user->can('view', $user));
        $this->assertFalse($user->can('view', new User));
    }

    /**
     * Admin can change their own data and other user data.
     *
     * @test
     * @group u-policy
     */
    public function updateAsAdmin()
    {
        $admin = $this->adminUser();

        $this->assertTrue($admin->can('update', $admin));
        $this->assertTrue($admin->can('update', new User));
    }

    /**
     * Non-admin can only change their own data.
     *
     * @test
     * @group u-policy
     */
    public function updateAsNormalUser()
    {
        $user = $this->normalUser();

        $this->assertTrue($user->can('update', $user));
        $this->assertFalse($user->can('update', new User));
    }

    /**
     * Admin can change their own data and other user data.
     *
     * @test
     * @group u-policy
     */
    public function updatePassAsAdmin()
    {
        $admin = $this->adminUser();

        $this->assertTrue($admin->can('updatePass', $admin));
        $this->assertTrue($admin->can('updatePass', new User));
    }

    /**
     * Non-admin can only change their own data.
     *
     * @test
     * @group u-policy
     */
    public function updatePassAsNormalUser()
    {
        $user = $this->normalUser();

        $this->assertTrue($user->can('updatePass', $user));
        $this->assertFalse($user->can('updatePass', new User));
    }

    //
    // Change Password.
    //
    protected function getCPRoute($value)
    {
        return route('user.change-password', $value);
    }

    /**
     * @test
     * @group u-policy
     */
    public function adminCanAccessChangePasswordPage()
    {
        $response = $this->actingAs($this->adminUser())
            ->get($this->getCPRoute($this->normalUser()->name));

        $response->assertOk();
    }

    /**
     * Normal user cant access other users change password page.
     *
     * @test
     * @group u-policy
     */
    public function normalUserCantAccessOtherUsersChangePasswordPage()
    {
        $response = $this->actingAs($this->normalUser())
            ->get($this->getCPRoute($this->adminUser()->name));

        $response->assertForbidden();
    }

    /** @test */
    public function normalUserCanAccessTheirOwnChangePasswordPage()
    {
        $response =$this->actingAs($this->adminUser())
            ->get($this->getCPRoute($this->adminUser()->name));

        $response->assertOk();
    }

    //
    // ALl Users Page.
    //

    /**
     * @test
     * @group u-policy
     */
    public function adminCanAccessAllUsersPage()
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('user.index'));

        $response->assertOk();
    }

    /**
     * @test
     */
    public function normalUserCantAccessAllUsersPage()
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('user.index'));

        $response->assertForbidden();
    }
}
