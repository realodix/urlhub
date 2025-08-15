<?php

namespace Tests\Feature\AuthPage\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
#[PHPUnit\Group('user-page')]
class NewUserTest extends TestCase
{
    private function getRoute(): string
    {
        return route('user.new');
    }

    private function postRoute(): string
    {
        return route('user.store');
    }

    #[PHPUnit\Test]
    public function access_Page_Admin_WillBeOk(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get($this->getRoute());
        $response->assertOk();
    }

    #[PHPUnit\Test]
    public function access_Page_BasicUser_WillBeForbidden(): void
    {
        $respons = $this->actingAs($this->basicUser())
            ->get($this->getRoute());
        $respons->assertForbidden();
    }

    public function testCreateNewUser(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->post($this->postRoute(), [
                'username' => 'test',
                'email' => 'test@urlhub.test',
                'password' => 'password',
            ]);

        $response->assertRedirect(route('user.edit', ['user' => 'test']));

        $user = User::where('name', 'test')->first();
        $this->assertEquals('test', $user->name);
        $this->assertEquals('test@urlhub.test', $user->email);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertFalse($user->hasRole('admin'));
    }

    public function testUsernameMustBeUnique(): void
    {
        $user = User::factory()->create(['name' => 'test']);
        $response = $this->actingAs($this->adminUser())
            ->post($this->postRoute(), [
                'username' => $user->name,
                'email' => 'test@urlhub.test',
                'password' => 'password',
            ]);

        $response->assertSessionHasErrors('username');
        $this->assertCount(2, User::all()); // 2 (adminUser & $user)
    }

    public function testStoreEmailAsLowerCase(): void
    {
        $this->post('/register', [
            'name' => 'usernametest',
            'email' => 'John@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $this->assertSame('john@example.com', User::first()->email);
    }

    public function testCreateNewUserWithRoleAdmin(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->post($this->postRoute(), [
                'username' => 'test',
                'email' => 'test@urlhub.test',
                'password' => 'password',
                'role' => 'admin',
            ]);

        $response->assertRedirect(route('user.edit', ['user' => 'test']));

        $user = User::where('name', 'test')->first();
        $this->assertEquals('test', $user->name);
        $this->assertEquals('test@urlhub.test', $user->email);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertTrue($user->hasRole('admin'));
    }

    public function testFormCannotBeFilledWithEmptyData(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->post($this->postRoute(), [
                'username' => '',
                'email' => '',
                'password' => '',
            ]);

        $response->assertSessionHasErrors(['username', 'email', 'password']);
    }
}
