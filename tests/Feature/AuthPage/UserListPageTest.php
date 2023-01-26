<?php

namespace Tests\Feature\AuthPage;

use Tests\TestCase;

class UserListPageTest extends TestCase
{
    /**
     * @test
     * @group f-userlist
     */
    public function ulpAdminCanAccessThisPage()
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('user.index'));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-userlist
     */
    public function ulpNormalUserCantAccessThisPage()
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('user.index'));

        $response->assertForbidden();
    }
}
