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
