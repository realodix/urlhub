<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected function successfulLoginRoute()
    {
        return route('home');
    }

    protected function getRoute()
    {
        return route('login');
    }

    protected function postRoute()
    {
        return route('login');
    }

    protected function guestMiddlewareRoute()
    {
        return route('home');
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCanViewALoginForm()
    {
        $response = $this->get($this->getRoute());

        $response
            ->assertSuccessful()
            ->assertViewIs('auth.login');
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotViewALoginFormWhenAuthenticated()
    {
        $response = $this->actingAs($this->nonAdmin())
            ->get($this->getRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCanLoginWithCorrectCredentials()
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

    /**
     * @test
     * @group f-auth
     */
    public function userCannotLoginWithIncorrectPassword()
    {
        $user = User::factory()->create([
            'password' => Hash::make('i-love-laravel'),
        ]);

        $response = $this->from($this->getRoute())->post($this->postRoute(), [
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

    /**
     * @test
     * @group f-auth
     */
    public function unauthenticatedUsersCantAccessTheDashboard()
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotLoginWithEmailThatDoesNotExist()
    {
        $response = $this->from($this->getRoute())->post($this->postRoute(), [
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
