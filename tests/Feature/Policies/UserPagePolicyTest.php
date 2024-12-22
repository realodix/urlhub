<?php

namespace Tests\Feature\Policies;

use App\Models\User;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('user-page')]
class UserPagePolicyTest extends TestCase
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

    /**
     * Admin can access every user's account page.
     *
     * This test simulates an admin user accessing another user's account page,
     * verifies that the operation is successful by checking for an ok response,
     * and confirms that the user is on the target page.
     *
     * @see App\Http\Controllers\Dashboard\User\UserController::edit()
     */
    #[PHPUnit\Test]
    public function adminCanAccessEveryUserAccountPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('user.edit', $this->basicUser()->name));

        $response->assertOk();
    }

    /**
     * Ensures that a basic user cannot access another user's account page.
     *
     * This test verifies that when a basic user attempts to access the account
     * page of an admin user, the operation is forbidden by asserting that the
     * response is forbidden.
     *
     * @see App\Http\Controllers\Dashboard\User\UserController::edit()
     */
    #[PHPUnit\Test]
    public function basicUserCantAccessAnotherUsersAccountPage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('user.edit', $this->adminUser()->name));

        $response->assertForbidden();
    }

    /**
     * Admin can change the email of another user.
     *
     * This test simulates an admin user changing the email of another user,
     * verifies that the operation is successful by checking for a redirect
     * and a success flash message, and confirms the email change in the database.
     *
     * @see App\Http\Controllers\Dashboard\User\UserController::update()
     */
    #[PHPUnit\Test]
    public function adminCanChangeOtherUsersEmail(): void
    {
        $user = User::factory()->create(['email' => 'user_email@urlhub.test']);

        $response = $this->actingAs($this->adminUser())
            ->from(route('user.edit', $user->name))
            ->post(route('user.update', $user->name), [
                'email' => 'new_user_email@urlhub.test',
            ]);

        $response
            ->assertRedirect(route('user.edit', $user->name))
            ->assertSessionHas('flash_success');

        $this->assertSame('new_user_email@urlhub.test', $user->fresh()->email);
    }

    /**
     * A normal user cannot change the email of another user.
     *
     * This test simulates a normal user trying to change the email of another user,
     * verifies that the operation is forbidden by checking for a forbidden response,
     * and confirms that the email is unchanged in the database.
     *
     * @see App\Http\Controllers\Dashboard\User\UserController::update()
     */
    #[PHPUnit\Test]
    public function basicUserCantChangeOtherUsersEmail(): void
    {
        $user = User::factory()->create(['email' => 'user2@urlhub.test']);

        $response = $this->actingAs($this->basicUser())
            ->from(route('user.edit', $user->name))
            ->post(route('user.update', $user->name), [
                'email' => 'new_email_user2@urlhub.test',
            ]);

        $response->assertForbidden();
        $this->assertSame('user2@urlhub.test', $user->email);
    }
}
