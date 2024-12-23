<?php

namespace Tests\Feature\AuthPage\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('user-page')]
class ChangePasswordTest extends TestCase
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
     * User can access change password page.
     */
    #[PHPUnit\Test]
    public function canAccessChangePasswordPage(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get($this->getRoute($user->name));

        $response->assertOk();
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
     * Test that a user can successfully change their password when providing
     * correct current and new password credentials.
     */
    #[PHPUnit\Test]
    public function changePasswordWithCorrectCredentials(): void
    {
        $response = $this->actingAs($this->user)
            ->from($this->getRoute($this->user->name))
            ->post($this->postRoute($this->user->name), [
                'current_password' => self::$password,
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

    /**
     * Test that a user cannot change their password if the current password is incorrect.
     *
     * This test ensures that a password change request fails when the provided
     * current password does not match the actual current password of the user.
     * It verifies that an error is returned for the 'current_password' field
     * and that the user's password remains unchanged.
     */
    #[PHPUnit\Test]
    public function currentPasswordDoesNotMatch(): void
    {
        $response = $this->actingAs($this->user)
            ->from($this->getRoute($this->user->name))
            ->post($this->postRoute($this->user->name), [
                'current_password' => 'laravel',
                'new_password'     => 'new-awesome-password',
                'new_password_confirmation' => 'new-awesome-password',
            ]);

        $response
            ->assertRedirect($this->getRoute($this->user->name))
            ->assertSessionHasErrors('current_password');

        $this->assertFalse(
            Hash::check('new-awesome-password', $this->user->fresh()->password),
        );
    }

    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('newPasswordFailProvider')]
    public function newPasswordValidateFail($data1, $data2): void
    {
        $user = $this->user;

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                'current_password' => self::$password,
                'new_password'     => $data1,
                'new_password_confirmation' => $data2,
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('new_password');

        $this->assertFalse(
            Hash::check($data1, $user->fresh()->password),
        );
    }

    public static function newPasswordFailProvider(): array
    {
        return [
            ['', ''], // required
            [null, null], // string
            ['new_password', 'new-pass-word'], // confirmed

            // Laravel NIST Password Rules
            // ['new-awe', 'new-awe'], // min:8
            // [str_repeat('a', 9), str_repeat('a', 9)], // repetitive
            // ['12345678', '12345678'], // sequential
        ];
    }

    /**
     * The new password must be different from the current password.
     */
    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('newPasswordFailProvider')]
    public function newPasswordmustBeDifferent(): void
    {
        $user = $this->user;

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                'current_password' => self::$password,
                'new_password'     => self::$password,
                'new_password_confirmation' => self::$password,
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('new_password');

        $this->assertTrue(
            Hash::check(self::$password, $user->fresh()->password),
        );
    }
}
