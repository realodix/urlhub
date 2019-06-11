<?php

namespace Tests\Unit\Policies;

use App\Url;
use App\User;
use Tests\TestCase;

class UrlPolicyTest extends TestCase
{
    /**
     * Determine whether the user can permanently delete the url.
     *
     * @test
     */
    public function force_delete_admin()
    {
        $this->loginAsAdmin();

        $admin = $this->admin();

        $this->assertTrue($admin->can('forceDelete', new Url));
    }

    /**
     * Determine whether the user can permanently delete the url.
     *
     * @test
     */
    public function force_delete_non_admin()
    {
        $this->loginAsUser();

        $non_admin = $this->user();

        $this->assertFalse($non_admin->can('forceDelete', new Url));
    }
}
