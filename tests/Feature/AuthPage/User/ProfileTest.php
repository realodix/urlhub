<?php

namespace Tests\Feature\AuthPage\User;

use App\Models\User;
use PHPUnit\Framework\Attributes\{Group, Test};
use Tests\TestCase;

class ProfileTest extends TestCase
{
    protected function getRoute(mixed $value): string
    {
        return route('user.edit', $value);
    }

    protected function postRoute(mixed $value): string
    {
        return route('user.update', $value);
    }

    #[Test]
    #[Group('f-user')]
    public function usersCanAccessTheirOwnProfilePage(): void
    {
        $user = $this->normalUser();
        $response = $this->actingAs($user)
            ->get($this->getRoute($user->name));

        $response->assertOk();
    }

    #[Test]
    #[Group('f-user')]
    public function adminCanAccessOtherUsersProfilePages(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get($this->getRoute($this->normalUser()->name));

        $response->assertOk();
    }

    #[Test]
    #[Group('f-user')]
    public function adminUserCantAccessOtherUsersProfilePages(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get($this->getRoute($this->adminUser()->name));

        $response->assertForbidden();
    }

    #[Test]
    #[Group('f-user')]
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

    #[Test]
    #[Group('f-user')]
    public function normalUserCantChangeOtherUsersEmail(): void
    {
        $user = User::factory()->create(['email' => 'user2@urlhub.test']);

        $response = $this->actingAs($this->normalUser())
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                'email' => 'new_email_user2@urlhub.test',
            ]);

        $response->assertForbidden();
        $this->assertSame('user2@urlhub.test', $user->email);
    }

    #[Test]
    #[Group('f-user')]
    public function validationEmailRequired(): void
    {
        $user = $this->normalUser();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                'email' => '',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }

    #[Test]
    #[Group('f-user')]
    public function validationEmailInvalidFormat(): void
    {
        $user = $this->normalUser();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                'email' => 'invalid_format',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }

    #[Test]
    #[Group('f-user')]
    public function validationEmailMaxLength(): void
    {
        $user = $this->normalUser();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                // 255 + 9
                'email' => str_repeat('a', 255).'@mail.com',
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }

    #[Test]
    #[Group('f-user')]
    public function validationEmailUnique(): void
    {
        $user = $this->normalUser();

        $response = $this->actingAs($user)
            ->from($this->getRoute($user->name))
            ->post($this->postRoute($user->name), [
                'email' => $this->normalUser()->email,
            ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }
}
