<?php

namespace Tests\Unit\Policies;

use App\Url;
use App\User;
use Tests\TestCase;

class UrlPolicyTest extends TestCase
{
    /**
     * Admin can delete their own data and other user data.
     *
     * @test
     */
    public function force_delete_admin()
    {
        $this->loginAsAdmin();

        $admin = $this->admin();
        $their_own_url = factory(Url::class)->create([
            'user_id'  => $admin->id,
            'long_url' => 'https://laravel.com',
        ]);

        $this->assertTrue($admin->can('forceDelete', $their_own_url));
        $this->assertTrue($admin->can('forceDelete', new Url));
    }

    /**
     * Non-admin can only delete their own data.
     *
     * @test
     */
    public function force_delete_non_admin()
    {
        $this->loginAsUser();

        $non_admin = $this->user();
        $their_own_url = factory(Url::class)->create([
            'user_id'  => $non_admin->id,
            'long_url' => 'https://laravel.com',
        ]);

        $this->assertTrue($non_admin->can('forceDelete', $their_own_url));
        $this->assertFalse($non_admin->can('forceDelete', new Url));
    }
}
