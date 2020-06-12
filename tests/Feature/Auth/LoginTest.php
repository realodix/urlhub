<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected function successfulLoginRoute()
    {
        return route('dashboard');
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
    public function user_can_view_a_login_form()
    {
        $response = $this->get($this->getRoute());

        $response
            ->assertSuccessful()
            ->assertViewIs('frontend.auth.login');
    }

    /**
     * @test
     * @group f-auth
     */
    public function user_cannot_view_a_login_form_when_authenticated()
    {
        $response = $this->loginAsUser()->get($this->getRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    /**
     * @test
     * @group f-auth
     */
    public function user_can_login_with_correct_credentials()
    {
        $user = factory(User::class)->create([
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
    public function user_cannot_login_with_incorrect_password()
    {
        $user = factory(User::class)->create([
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
    public function unauthenticated_users_cant_access_the_dashboard()
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    /**
     * @test
     * @group f-auth
     */
    public function user_cannot_login_with_email_that_does_not_exist()
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
