<?php

namespace Tests\Feature\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('user-page')]
class ChangePasswordPolicyTest extends TestCase
{
    protected User $user;

    protected static string $password = 'old-password';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => bcrypt(self::$password),
        ]);
    }

    protected function getRoute(mixed $value): string
    {
        return route('user.password.show', $value);
    }

    protected function postRoute(mixed $value): string
    {
        return route('user.password.store', $value);
    }

    /**
     * Admin users can access other user's change password page.
     *
     * This test simulates an admin user accessing the change password page of
     * another user, verifies that the operation is successful by checking for
     * a successful response.
     */
    #[PHPUnit\Test]
    public function adminCanAccessOtherUsersChangePasswordPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get($this->getRoute($this->basicUser()->name));

        $response->assertOk();
    }

    /**
     * Basic users can't access other user's change password page.
     *
     * This test simulates a normal user trying to access the change password
     * page of an admin user, verifies that the operation is forbidden by
     * checking for a forbidden response.
     */
    #[PHPUnit\Test]
    public function basicUserCantAccessOtherUsersChangePasswordPage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get($this->getRoute($this->adminUser()->name));

        $response->assertForbidden();
    }

    /**
     * Admin can change the password of all users.
     *
     * This test simulates an admin user changing the password of another user,
     * verifies that the operation is successful by checking for a redirect
     * and a success flash message, and confirms the password change in the
     * database.
     */
    #[PHPUnit\Test]
    public function adminCanChangeOtherUsersPassword(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->from($this->getRoute($this->user->name))
            ->post($this->postRoute($this->user->name), [
                // An Admin will enter their own password, not the password of a User
                'current_password' => self::$adminPass,
                'new_password'     => 'new-awesome-password',
                'new_password_confirmation' => 'new-awesome-password',
            ]);

        $response
            ->assertRedirect($this->getRoute($this->user->name))
            ->assertSessionHas('flash_success');

        $this->assertTrue(
            Hash::check('new-awesome-password', $this->user->fresh()->password),
        );
    }

    /**
     * Basic users can't change the password of other users.
     *
     * This test simulates a basic user trying to change the password of another
     * user, verifies that the operation is forbidden by checking for a forbidden
     * response, and confirms that the password is unchanged in the database.
     */
    #[PHPUnit\Test]
    public function basicUserCantChangeOtherUsersPassword(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->from($this->getRoute($this->user->name))
            ->post($this->postRoute($this->user->name), [
                'current_password' => $this->user->password,
                'new_password'     => 'new-awesome-password',
                'new_password_confirmation' => 'new-awesome-password',
            ]);

        $response->assertForbidden();
        $this->assertFalse(
            Hash::check('new-awesome-password', $this->user->fresh()->password),
        );
    }
}
