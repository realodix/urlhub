<?php

namespace Tests\Unit\Policies;

use App\User;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    /**
     * Admin can access their own the page and other user pages.
     *
     * @test
     * @group u-policy
     */
    public function view_admin()
    {
        $this->loginAsAdmin();

        $admin = $this->admin();

        $this->assertTrue($admin->can('view', $admin));
        $this->assertTrue($admin->can('view', new User()));
    }

    /**
     * Non-admin can only access their own page.
     *
     * @test
     * @group u-policy
     */
    public function view_non_admin()
    {
        $this->loginAsUser();

        $user = $this->user();

        $this->assertTrue($user->can('view', $user));
        $this->assertFalse($user->can('view', new User()));
    }

    /**
     * Admin can change their own data and other user data.
     *
     * @test
     * @group u-policy
     */
    public function update_admin()
    {
        $this->loginAsAdmin();

        $admin = $this->admin();

        $this->assertTrue($admin->can('update', $admin));
        $this->assertTrue($admin->can('update', new User()));
    }

    /**
     * Non-admin can only change their own data.
     *
     * @test
     * @group u-policy
     */
    public function update_non_admin()
    {
        $this->loginAsUser();

        $user = $this->user();

        $this->assertTrue($user->can('update', $user));
        $this->assertFalse($user->can('update', new User()));
    }

    /**
     * Admin can change their own data and other user data.
     *
     * @test
     * @group u-policy
     */
    public function updatePass_admin()
    {
        $this->loginAsAdmin();

        $admin = $this->admin();

        $this->assertTrue($admin->can('updatePass', $admin));
        $this->assertTrue($admin->can('updatePass', new User()));
    }

    /**
     * Non-admin can only change their own data.
     *
     * @test
     * @group u-policy
     */
    public function updatePass_non_admin()
    {
        $this->loginAsUser();

        $user = $this->user();

        $this->assertTrue($user->can('updatePass', $user));
        $this->assertFalse($user->can('updatePass', new User()));
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
    public function admin_can_access_change_password_page()
    {
        $this->loginAsAdmin();

        $response = $this->get($this->getCPRoute($this->user()->name));
        $response->assertOk();
    }

    /**
     * @test
     * @group u-policy
     */
    public function non_admin_cant_access_change_password_page()
    {
        $this->loginAsUser();

        $response = $this->get($this->getCPRoute($this->admin()->name));
        $response->assertForbidden();
    }

    /** @test */
    public function users_can_access_their_own_change_password_page()
    {
        $this->loginAsAdmin();

        $response = $this->get($this->getCPRoute($this->admin()->name));
        $response->assertOk();
    }

    //
    // ALl Users Page.
    //

    /**
     * @test
     * @group u-policy
     */
    public function admin_can_access_all_users_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('user.index'));
        $response->assertOk();
    }

    /**
     * @test
     */
    public function non_admin_cant_access_all_users_page()
    {
        $this->loginAsUser();

        $response = $this->get(route('user.index'));
        $response->assertForbidden();
    }
}
