<?php

namespace Tests\Unit\Policies;

use App\Url;
use App\User;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Policies\UrlPolicy
 */
class UrlPolicyTest extends TestCase
{
    /**
     * Admin can delete their own data and other user data.
     *
     * @test
     * @group u-policy
     * @covers ::forceDelete
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
     * @group u-policy
     * @covers ::forceDelete
     */
    public function force_delete_non_admin()
    {
        $this->loginAsUser();

        $user = $this->user();
        $their_own_url = factory(Url::class)->create([
            'user_id'  => $user->id,
            'long_url' => 'https://laravel.com',
        ]);

        $this->assertTrue($user->can('forceDelete', $their_own_url));
        $this->assertFalse($user->can('forceDelete', new Url));
    }
}
