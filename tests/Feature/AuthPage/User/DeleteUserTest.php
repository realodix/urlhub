<?php

namespace Tests\Feature\AuthPage\User;

use App\Models\User;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('user-page')]
class DeleteUserTest extends TestCase
{
    private function getRoute($user): string
    {
        return route('user.delete', $user);
    }

    public function testDeleteUserFromList(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->adminUser())
            ->from(route('user.delete.confirm', $user))
            ->delete($this->getRoute($user));

        $response
            ->assertRedirectToRoute('user.index')
            ->assertSessionHas('flash_success');
        $this->assertNull(User::find($user->id));
    }
}
