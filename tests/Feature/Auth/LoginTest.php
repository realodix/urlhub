<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
class LoginTest extends TestCase
{
    protected function successfulLoginRoute(): string
    {
        return route('home');
    }

    protected function getRoute(): string
    {
        return route('login');
    }

    protected function postRoute(): string
    {
        return route('login');
    }

    #[PHPUnit\Test]
    public function userCanViewALoginForm(): void
    {
        $response = $this->get($this->getRoute());

        $response->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function userCanSeeTheLoginPage(): void
    {
        $response = $this->get($this->getRoute());

        $response->assertSuccessful()
            ->assertViewIs('auth.login');
    }

    #[PHPUnit\Test]
    public function userCannotViewALoginFormWhenAuthenticated(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get($this->getRoute());

        $response->assertRedirect(route('dashboard'));
    }

    #[PHPUnit\Test]
    public function userCanLoginWithCorrectCredentials(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);

        $response = $this->post($this->postRoute(), [
            'identity' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect($this->successfulLoginRoute());
        $this->assertAuthenticatedAs($user);
    }

    #[PHPUnit\Test]
    public function userCannotLoginWithIncorrectPassword(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('i-love-laravel'),
        ]);

        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), [
                'identity' => $user->email,
                'password' => 'invalid-password',
            ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('error');

        $this->assertTrue(session()->hasOldInput('identity'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    #[PHPUnit\Test]
    public function unauthenticatedUsersCantAccessTheDashboard(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    #[PHPUnit\Test]
    public function userCannotLoginWithEmailThatDoesNotExist(): void
    {
        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), [
                'identity' => 'nobody@example.com',
                'password' => 'invalid-password',
            ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('error');

        $this->assertTrue(session()->hasOldInput('identity'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
