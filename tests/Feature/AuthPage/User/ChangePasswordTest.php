<?php

namespace Tests\Feature\AuthPage\User;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
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
        $user = $this->adminUser();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'current-password'          => self::$adminPass,
                'new-password'              => 'new-awesome-password',
                'new-password_confirmation' => 'new-awesome-password',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHas('flash_success');

        $this->assertTrue(
            Hash::check('new-awesome-password', $user->fresh()->password)
        );
    }

    /**
     * @test
     * @group f-user
     */
    public function adminCanChangeThePasswordOfAllUsers(): void
    {
        $user = $this->normalUser();

        $response = $this->actingAs($this->adminUser())
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'current-password'          => self::$adminPass,
                'new-password'              => 'new-awesome-password',
                'new-password_confirmation' => 'new-awesome-password',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHas('flash_success');

        $this->assertTrue(
            Hash::check('new-awesome-password', $user->fresh()->password)
        );
    }

    /**
     * @test
     * @group f-user
     */
    public function currentPasswordDoesNotMatch(): void
    {
        $user = $this->adminUser();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'current-password'          => 'laravel',
                'new-password'              => 'new-awesome-password',
                'new-password_confirmation' => 'new-awesome-password',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('current-password');

        $this->assertFalse(
            Hash::check('new-awesome-password', $user->fresh()->password)
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
        $user = $this->normalUser();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'current-password'          => self::$adminPass,
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
            [self::$adminPass, self::$adminPass], // different
            [null, null], // string
            ['new-password', 'new-pass-word'], // confirmed

            // Laravel NIST Password Rules
            // ['new-awe', 'new-awe'], // min:8
            // [str_repeat('a', 9), str_repeat('a', 9)], // repetitive
            // ['12345678', '12345678'], // sequential
        ];
    }
}
