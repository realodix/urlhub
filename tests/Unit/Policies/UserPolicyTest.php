<?php

namespace Tests\Unit\Policies;

use App\User;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    /**
     * Admin can access the page and other user pages.
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
     * Non-admin can only access own page.
     *
     * @test
     */
    public function view_non_admin()
    {
        $this->loginAsUser();

        $non_admin = $this->user();

        $this->assertTrue($non_admin->can('view', $non_admin));
        $this->assertFalse($non_admin->can('view', new User()));
    }

    /**
     * Admin can change the password for himself and other users.
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
     * Not an Admin can only change his own password.
     *
     * @test
     */
    public function update_non_admin()
    {
        $this->loginAsUser();

        $non_admin = $this->user();

        $this->assertTrue($non_admin->can('update', $non_admin));
        $this->assertFalse($non_admin->can('update', new User()));
    }

    /**
     * Admin can access the page and other user pages.
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
     * Non-admin can only access own page.
     *
     * @test
     */
    public function updatePass_non_admin()
    {
        $this->loginAsUser();

        $non_admin = $this->user();

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

        $response = $this->get($this->getCPRoute($this->user()->name));
        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_cant_access_change_password_page()
    {
        $this->loginAsUser();

        $response = $this->get($this->getCPRoute($this->admin()->name));
        $response->assertStatus(403);
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
        $this->loginAsUser();

        $response = $this->get(route('user.index'));
        $response->assertStatus(403);
    }
}
