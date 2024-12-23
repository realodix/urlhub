<?php

namespace Tests\Feature\AuthPage;

use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('user-page')]
class UserListPageTest extends TestCase
{
    /**
     * A user with the admin role should be able to access the user list page.
     * This test asserts that an admin user is able to access the page.
     *
     * @see App\Http\Controllers\Dashboard\User\UserController::view()
     */
    #[PHPUnit\Test]
    public function canAccessUserListPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('user.index'));
        $response->assertOk();
    }

    /**
     * A basic user should not be able to access the user list page. This test
     * asserts that a normal user is redirected to the forbidden page.
     *
     * @see App\Http\Controllers\Dashboard\User\UserController::view()
     */
    #[PHPUnit\Test]
    public function basicUserCantAccessUserListPage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('user.index'));
        $response->assertForbidden();
    }
}
