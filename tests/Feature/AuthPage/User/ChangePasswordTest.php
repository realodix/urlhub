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

    #[PHPUnit\Test]
    public function changePasswordWithCorrectCredentials(): void
    {
        $response = $this->actingAs($this->user)
            ->from($this->getRoute($this->user->name))
            ->post($this->postRoute($this->user->name), [
                'current-password' => self::$password,
                'new-password'     => 'new-awesome-password',
                'new-password_confirmation' => 'new-awesome-password',
            ]);

        $response
            ->assertRedirect($this->getRoute($this->user->name))
            ->assertSessionHas('flash_success');

        $this->assertTrue(
            Hash::check('new-awesome-password', $this->user->fresh()->password)
        );
    }

    #[PHPUnit\Test]
    public function adminCanChangeThePasswordOfAllUsers(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->from($this->getRoute($this->user->name))
            ->post($this->postRoute($this->user->name), [
                // An Admin will enter their own password, not the password of a User
                'current-password' => self::$adminPass,
                'new-password'     => 'new-awesome-password',
                'new-password_confirmation' => 'new-awesome-password',
            ]);

        $response
            ->assertRedirect($this->getRoute($this->user->name))
            ->assertSessionHas('flash_success');

        $this->assertTrue(
            Hash::check('new-awesome-password', $this->user->fresh()->password)
        );
    }

    #[PHPUnit\Test]
    public function currentPasswordDoesNotMatch(): void
    {
        $response = $this->actingAs($this->user)
            ->from($this->getRoute($this->user->name))
            ->post($this->postRoute($this->user->name), [
                'current-password' => 'laravel',
                'new-password'     => 'new-awesome-password',
                'new-password_confirmation' => 'new-awesome-password',
            ]);

        $response
            ->assertRedirect($this->getRoute($this->user->name))
            ->assertSessionHasErrors('current-password');

        $this->assertFalse(
            Hash::check('new-awesome-password', $this->user->fresh()->password)
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
                'current-password' => self::$password,
                'new-password'     => $data1,
                'new-password_confirmation' => $data2,
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('new-password');

        $this->assertFalse(
            Hash::check($data1, $user->fresh()->password)
        );
    }

    public static function newPasswordFailProvider(): array
    {
        return [
            ['', ''], // required
            [null, null], // string
            ['new-password', 'new-pass-word'], // confirmed

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
                'current-password' => self::$password,
                'new-password'     => self::$password,
                'new-password_confirmation' => self::$password,
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('new-password');

        $this->assertTrue(
            Hash::check(self::$password, $user->fresh()->password)
        );
    }
}
