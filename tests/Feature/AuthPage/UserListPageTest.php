<?php

namespace Tests\Feature\AuthPage;

use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('link-page')]
class UserListPageTest extends TestCase
{
    #[PHPUnit\Test]
    public function ulpAdminCanAccessThisPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('user.index'));

        $response->assertOk();
    }

    #[PHPUnit\Test]
    public function ulpNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('user.index'));

        $response->assertForbidden();
    }
}
