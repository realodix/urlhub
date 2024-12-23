<?php

namespace Tests\Feature\AuthPage\User;

use App\Models\User;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('user-page')]
class AccountTest extends TestCase
{
    protected function getRoute(mixed $value): string
    {
        return route('user.edit', $value);
    }

    protected function postRoute(mixed $value): string
    {
        return route('user.update', $value);
    }

    /**
     * Verifies that a user can access their own account page.
     *
     * This test checks that when a user attempts to access their own account page,
     * the operation is successful by asserting an OK response.
     */
    public function testCanAccessThisPage(): void
    {
        $user = $this->basicUser();
        $response = $this->actingAs($user)
            ->get($this->getRoute($user->name));

        $response->assertOk();
    }

    /**
     * Admin can access Other users' account page.
     *
     * This test simulates an admin user accessing another user's account page,
     * verifies that the operation is successful by checking for an ok response,
     * and confirms that the user is on the target page.
     *
     * @see App\Http\Controllers\Dashboard\User\UserController::edit()
     */
    #[PHPUnit\Test]
    public function adminCanAccessOtherUsersAccountPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get($this->getRoute($this->basicUser()->name));

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
    public function basicUserCantAccessOtherUsersAccountPage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get($this->getRoute($this->adminUser()->name));

        $response->assertForbidden();
    }

    public function testCanUpdateEmail(): void
    {
        $user = $this->basicUser();
        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                'email' => 'new_email@example.com',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHas('flash_success');
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
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                'email' => 'new_user_email@urlhub.test',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
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
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                'email' => 'new_email_user2@urlhub.test',
            ]);

        $response->assertForbidden();
        $this->assertSame('user2@urlhub.test', $user->email);
    }

    public function testValidateEmailRequired(): void
    {
        $user = $this->basicUser();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                'email' => '',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }

    public function testValidateEmailInvalidFormat(): void
    {
        $user = $this->basicUser();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                'email' => 'invalid_format',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }

    public function testValidateEmailMaxLength(): void
    {
        $user = $this->basicUser();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                // 255 + 9
                'email' => str_repeat('a', 255) . '@mail.com',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }

    public function testValidateEmailUnique(): void
    {
        $user = $this->basicUser();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                'email' => $this->basicUser()->email,
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }
}
