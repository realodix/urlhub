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
        $response = $this->actingAs($this->admin())
            ->get(route('user.index'));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-userlist
     */
    public function ulpNonAdminCantAccessThisPage()
    {
        $response = $this->actingAs($this->nonAdmin())
            ->get(route('user.index'));

        $response->assertForbidden();
    }
}
