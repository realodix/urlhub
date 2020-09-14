<?php

namespace Tests\Unit\Policies;

use App\Models\Url;
use Tests\TestCase;

class UrlPolicyTest extends TestCase
{
    /**
     * Admin can delete their own data and other user data.
     *
     * @test
     * @group u-policy
     */
    public function force_delete_admin()
    {
        $this->loginAsAdmin();

        $admin = $this->admin();
        $url = Url::factory()->create([
            'user_id'  => $admin->id,
            'long_url' => 'https://laravel.com',
        ]);

        $this->assertTrue($admin->can('forceDelete', $url));
        $this->assertTrue($admin->can('forceDelete', new Url));
    }

    /**
     * Non-admin can only delete their own data.
     *
     * @test
     * @group u-policy
     */
    public function force_delete_non_admin()
    {
        $this->loginAsNonAdmin();

        $user = $this->nonAdmin();
        $url = Url::factory()->create([
            'user_id'  => $user->id,
            'long_url' => 'https://laravel.com',
        ]);

        $this->assertTrue($user->can('forceDelete', $url));
        $this->assertFalse($user->can('forceDelete', new Url));
    }
}
