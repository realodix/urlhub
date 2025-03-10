<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
class LoginTest extends TestCase
{
    private function successfulLoginRoute(): string
    {
        return route('home');
    }

    private function getRoute(): string
    {
        return route('login');
    }

    private function postRoute(): string
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

    /**
     * Test that an authenticated user is redirected to the dashboard
     * and cannot view the login form.
     */
    #[PHPUnit\Test]
    public function userCannotViewALoginFormWhenAuthenticated(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get($this->getRoute());

        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Test that a user can login with their correct credentials.
     */
    #[PHPUnit\Test]
    public function userCanLoginWithCorrectCredentials(): void
    {
        $user = User::factory()->create([
            'password' => $password = 'i-love-laravel',
        ]);

        $response = $this->post($this->postRoute(), [
            'identity' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect($this->successfulLoginRoute());
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test that a user cannot login with an incorrect password.
     */
    #[PHPUnit\Test]
    public function userCannotLoginWithIncorrectPassword(): void
    {
        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), [
                'identity' => $this->basicUser()->email,
                'password' => 'invalid-password',
            ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('error');

        $this->assertTrue(session()->hasOldInput('identity'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * Test that unauthenticated users cannot access the dashboard and are
     * redirected to the login page.
     */
    #[PHPUnit\Test]
    public function unauthenticatedUsersCantAccessTheDashboard(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    /**
     * Test that a user cannot login with an email that doesn't exist in the
     * database.
     */
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
