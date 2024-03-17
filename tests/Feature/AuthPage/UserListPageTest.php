<?php

namespace Tests\Feature\AuthPage;

use PHPUnit\Framework\Attributes\{Group, Test};
use Tests\TestCase;

class UserListPageTest extends TestCase
{
    #[Test]
    #[Group('f-userlist')]
    public function ulpAdminCanAccessThisPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('user.index'));

        $response->assertOk();
    }

    #[Test]
    #[Group('f-userlist')]
    public function ulpNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('user.index'));

        $response->assertForbidden();
    }
}
