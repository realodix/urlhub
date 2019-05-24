<?php

namespace Tests\Unit\Policies;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_forceDelete(User $user, Url $url)
    {
        $admin = factory(User::class)->create();
    }

    /** @test */
    public function user_forceDelete(User $user, Url $url)
    {
        $user = factory(User::class)->create();
    }
}
