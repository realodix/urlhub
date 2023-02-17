<?php

namespace Tests\Feature\AuthPage;

use Tests\TestCase;

class UserListPageTest extends TestCase
{
    /**
     * @test
     * @group f-userlist
     */
    public function ulpAdminCanAccessThisPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('user.index'));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-userlist
     */
    public function ulpNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('user.index'));

        $response->assertForbidden();
    }
}
