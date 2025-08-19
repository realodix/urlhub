<?php

namespace Tests\Feature\AuthPage\User;

use App\Models\User;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('user-page')]
class DeleteUserTest extends TestCase
{
    #[PHPUnit\Test]
    public function delete_OtherUser_ByAdmin_WillBeOk(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->adminUser())
            ->from(route('user.delete.confirm', $user))
            ->delete(route('user.delete', $user));

        $response
            ->assertRedirectToRoute('user.index')
            ->assertSessionHas('flash_success');
        $this->assertNull(User::find($user->id));
    }

    #[PHPUnit\Test]
    public function delete_OtherUser_ByUser_WillBeForbidden(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->basicUser())
            ->delete(route('user.delete', $user));

        $response->assertForbidden();
        $this->assertNotNull(User::find($user->id));
    }

    #[PHPUnit\Test]
    public function delete_OwnAccount_ByAdmin_WillBeForbidden(): void
    {
        $admin = $this->adminUser();
        $response = $this->actingAs($admin)
            ->delete(route('user.delete', $admin));

        $response->assertForbidden();
        $this->assertNotNull(User::find($admin->id));
    }

    #[PHPUnit\Test]
    public function delete_OwnAccount_ByUser_WillBeForbidden(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->delete(route('user.delete', $user));

        $response->assertForbidden();
        $this->assertNotNull(User::find($user->id));
    }

    #[PHPUnit\DataProvider('acdpWithOtherUserProvider')]
    #[PHPUnit\Test]
    public function access_ConfirmDeletePage_ForOtherUser($user, $actingAs, $expectedStatus): void
    {
        $user = $user($this);
        $response = $this->actingAs($actingAs($this))
            ->get(route('user.delete.confirm', $user));

        $response->assertStatus($expectedStatus);
    }

    public static function acdpWithOtherUserProvider(): array
    {
        return [
            'Admin can access other user deletion confirmation page' => [
                'user' => fn(TestCase $tc) => User::factory()->create(),
                'actingAs' => fn(TestCase $tc) => $tc->adminUser(),
                'expectedStatus' => 200,
            ],
            'User cant access other user deletion confirmation page' => [
                'user' => fn(TestCase $tc) => User::factory()->create(),
                'actingAs' => fn(TestCase $tc) => $tc->basicUser(),
                'expectedStatus' => 403,
            ],
        ];
    }

    #[PHPUnit\DataProvider('acdpThemselvesProvider')]
    #[PHPUnit\Test]
    public function access_ConfirmDeletePage_ForSelf($user): void
    {
        $user = $user($this);
        $response = $this->actingAs($user)
            ->get(route('user.delete.confirm', $user));

        $response->assertStatus(403);
    }

    public static function acdpThemselvesProvider(): array
    {
        return [
            'Admins cant access their own deletion confirmation page' => [
                'user' => fn(TestCase $tc) => $tc->adminUser(),
            ],
            'Users cant access their own deletion confirmation page' => [
                'user' => fn(TestCase $tc) => User::factory()->create(),
            ],
        ];
    }
}
