<?php

namespace Tests\Feature\User;

use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class AllUsersPageTest extends TestCase
{
    use MigrateFreshSeedOnce;

    /** @test */
    public function admin_can_access_all_users_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('user.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function user_cant_access_all_users_page()
    {
        $this->loginAsUser();

        $response = $this->get(route('user.index'));
        $response->assertStatus(403);
    }
}
