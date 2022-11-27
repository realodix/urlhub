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
    public function viewAdmin()
    {
        $admin = $this->admin();

        $this->assertTrue($admin->can('view', $admin));
        $this->assertTrue($admin->can('view', new User));
    }

    /**
     * Non-admin can only access their own page.
     *
     * @test
     * @group u-policy
     */
    public function viewNonAdmin()
    {
        $user = $this->nonAdmin();

        $this->assertTrue($user->can('view', $user));
        $this->assertFalse($user->can('view', new User));
    }

    /**
     * Admin can change their own data and other user data.
     *
     * @test
     * @group u-policy
     */
    public function updateAdmin()
    {
        $admin = $this->admin();

        $this->assertTrue($admin->can('update', $admin));
        $this->assertTrue($admin->can('update', new User));
    }

    /**
     * Non-admin can only change their own data.
     *
     * @test
     * @group u-policy
     */
    public function updateNonAdmin()
    {
        $user = $this->nonAdmin();

        $this->assertTrue($user->can('update', $user));
        $this->assertFalse($user->can('update', new User));
    }

    /**
     * Admin can change their own data and other user data.
     *
     * @test
     * @group u-policy
     */
    public function updatePassAdmin()
    {
        $admin = $this->admin();

        $this->assertTrue($admin->can('updatePass', $admin));
        $this->assertTrue($admin->can('updatePass', new User));
    }

    /**
     * Non-admin can only change their own data.
     *
     * @test
     * @group u-policy
     */
    public function updatePassNonAdmin()
    {
        $user = $this->nonAdmin();

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
        $response = $this->actingAs($this->admin())
            ->get($this->getCPRoute($this->nonAdmin()->name));

        $response->assertOk();
    }

    /**
     * @test
     * @group u-policy
     */
    public function nonAdminCantAccessChangePasswordPage()
    {
        $response = $this->actingAs($this->nonAdmin())
            ->get($this->getCPRoute($this->admin()->name));

        $response->assertForbidden();
    }

    /** @test */
    public function usersCanAccessTheirOwnChangePasswordPage()
    {
        $response =$this->actingAs($this->admin())
            ->get($this->getCPRoute($this->admin()->name));

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
        $response = $this->actingAs($this->admin())
            ->get(route('user.index'));

        $response->assertOk();
    }

    /**
     * @test
     */
    public function nonAdminCantAccessAllUsersPage()
    {
        $response = $this->actingAs($this->nonAdmin())
            ->get(route('user.index'));

        $response->assertForbidden();
    }
}
