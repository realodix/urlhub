<?php

namespace Tests\Feature\AuthPage;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('auth-page')]
#[\PHPUnit\Framework\Attributes\Group('link-page')]
class UserListPageTest extends TestCase
{
    #[Test]
    public function ulpAdminCanAccessThisPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('user.index'));

        $response->assertOk();
    }

    #[Test]
    public function ulpNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('user.index'));

        $response->assertForbidden();
    }
}
