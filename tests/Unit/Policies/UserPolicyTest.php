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
     */
    public function view_non_admin()
    {
        $this->loginAsNonAdmin();

        $non_admin = $this->nonAdmin();

        $this->assertTrue($non_admin->can('view', $non_admin));
        $this->assertFalse($non_admin->can('view', new User()));
    }

    /**
     * Admin can change their own data and other user data.
     *
     * @test
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
     */
    public function update_non_admin()
    {
        $this->loginAsNonAdmin();

        $non_admin = $this->nonAdmin();

        $this->assertTrue($non_admin->can('update', $non_admin));
        $this->assertFalse($non_admin->can('update', new User()));
    }

    /**
     * Admin can change their own data and other user data.
     *
     * @test
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
     */
    public function updatePass_non_admin()
    {
        $this->loginAsNonAdmin();

        $non_admin = $this->nonAdmin();

        $this->assertTrue($non_admin->can('updatePass', $non_admin));
        $this->assertFalse($non_admin->can('updatePass', new User()));
    }

    /*
     *
     * Change Password.
     *
     */

    protected function getCPRoute($value)
    {
        return route('user.change-password', $value);
    }

    /** @test */
    public function admin_can_access_change_password_page()
    {
        $this->loginAsAdmin();

        $response = $this->get($this->getCPRoute($this->nonAdmin()->name));
        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_cant_access_change_password_page()
    {
        $this->loginAsNonAdmin();

        $response = $this->get($this->getCPRoute($this->admin()->name));
        $response->assertForbidden();
    }

    /*
     *
     * ALl Users Page.
     *
     */

    /** @test */
    public function admin_can_access_all_users_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('user.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_cant_access_all_users_page()
    {
        $this->loginAsNonAdmin();

        $response = $this->get(route('user.index'));
        $response->assertForbidden();
    }
}
