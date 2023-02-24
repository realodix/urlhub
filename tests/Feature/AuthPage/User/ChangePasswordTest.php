<?php

namespace Tests\Feature\AuthPage\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

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
        return route('user.change-password', $value);
    }

    protected function postRoute(mixed $value): string
    {
        return $this->secureRoute('user.change-password.post', $value);
    }

    /**
     * @test
     * @group f-user
     */
    public function changePasswordWithCorrectCredentials(): void
    {
        $response = $this->actingAs($this->user)
            ->from($this->getRoute($this->user->name))
            ->post($this->postRoute($this->user->id), [
                'current-password'          => self::$password,
                'new-password'              => 'new-awesome-password',
                'new-password_confirmation' => 'new-awesome-password',
            ]);

        $response
            ->assertRedirect($this->getRoute($this->user->name))
            ->assertSessionHas('flash_success');

        $this->assertTrue(
            Hash::check('new-awesome-password', $this->user->fresh()->password)
        );
    }

    /**
     * @test
     * @group f-user
     */
    public function adminCanChangeThePasswordOfAllUsers(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->from($this->getRoute($this->user->name))
            ->post($this->postRoute($this->user->id), [
                // An Admin will enter their own password, not the password of a User
                'current-password'          => self::$adminPass,
                'new-password'              => 'new-awesome-password',
                'new-password_confirmation' => 'new-awesome-password',
            ]);

        $response
            ->assertRedirect($this->getRoute($this->user->name))
            ->assertSessionHas('flash_success');

        $this->assertTrue(
            Hash::check('new-awesome-password', $this->user->fresh()->password)
        );
    }

    /**
     * @test
     * @group f-user
     */
    public function currentPasswordDoesNotMatch(): void
    {
        $response = $this->actingAs($this->user)
            ->from($this->getRoute($this->user->name))
            ->post($this->postRoute($this->user->id), [
                'current-password'          => 'laravel',
                'new-password'              => 'new-awesome-password',
                'new-password_confirmation' => 'new-awesome-password',
            ]);

        $response
            ->assertRedirect($this->getRoute($this->user->name))
            ->assertSessionHasErrors('current-password');

        $this->assertFalse(
            Hash::check('new-awesome-password', $this->user->fresh()->password)
        );
    }

    /**
     * @test
     * @group f-user
     * @dataProvider newPasswordFailProvider
     *
     * @param mixed $data1
     * @param mixed $data2
     */
    public function newPasswordValidateFail($data1, $data2): void
    {
        $user = $this->user;

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'current-password'          => self::$password,
                'new-password'              => $data1,
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
     *
     * @test
     * @group f-user
     * @dataProvider newPasswordFailProvider
     */
    public function newPasswordmustBeDifferent(): void
    {
        $user = $this->user;

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'current-password'          => self::$password,
                'new-password'              => self::$password,
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
