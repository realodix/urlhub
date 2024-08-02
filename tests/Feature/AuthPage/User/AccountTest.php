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

    public function testUsersCanAccessTheirOwnAccountPages(): void
    {
        $user = $this->basicUser();
        $response = $this->actingAs($user)
            ->get($this->getRoute($user->name));

        $response->assertOk();
    }

    public function testAdminCanAccessEveryUserAccountPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get($this->getRoute($this->basicUser()->name));

        $response->assertOk();
    }

    public function testUserCannotAccessAnotherUserSAccountPage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get($this->getRoute($this->adminUser()->name));

        $response->assertForbidden();
    }

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

    #[PHPUnit\Test]
    public function normalUserCantChangeOtherUsersEmail(): void
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

    #[PHPUnit\Test]
    public function validationEmailRequired(): void
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

    #[PHPUnit\Test]
    public function validationEmailInvalidFormat(): void
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

    #[PHPUnit\Test]
    public function validationEmailMaxLength(): void
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

    #[PHPUnit\Test]
    public function validationEmailUnique(): void
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
