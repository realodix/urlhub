<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    protected function successfulRegistrationRoute()
    {
        return route('home');
    }

    protected function getRoute()
    {
        return route('register');
    }

    protected function postRoute()
    {
        return route('register');
    }

    protected function guestMiddlewareRoute()
    {
        return route('home');
    }

    public function test_user_can_view_a_registration_form()
    {
        $response = $this->get($this->getRoute());

        $response
            ->assertSuccessful()
            ->assertViewIs('frontend.auth.register');
    }

    public function test_user_cannot_view_a_registration_form_when_authenticated()
    {
        $response = $this->loginAsUser()->get($this->getRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function test_user_can_register()
    {
        Event::fake();

        $response = $this->post($this->postRoute(), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $response->assertRedirect($this->successfulRegistrationRoute());
        $this->assertCount(3, User::all());
        $this->assertAuthenticatedAs($user = User::whereName('John Doe')->first());
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('i-love-laravel', $user->password));
        Event::assertDispatched(Registered::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    public function test_name_should_not_be_too_long()
    {
        $response = $this->post('/register', [
            'name' => str_repeat('a', 51),
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors('name');
    }

    public function test_user_cannot_register_without_name()
    {
        $response = $this->from($this->getRoute())
                         ->post($this->postRoute(), [
                             'name'     => '',
                             'email'    => 'john@example.com',
                             'password' => 'i-love-laravel',
                             'password_confirmation' => 'i-love-laravel',
                         ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('name');

        $this->assertCount(2, User::all());
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_user_cannot_register_without_email()
    {
        $response = $this->from($this->getRoute())
                         ->post($this->postRoute(), [
                             'name'     => 'John Doe',
                             'email'    => '',
                             'password' => 'i-love-laravel',
                             'password_confirmation' => 'i-love-laravel',
                         ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');

        $this->assertCount(2, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_user_cannot_register_with_invalid_email()
    {
        $response = $this->from($this->getRoute())
                         ->post($this->postRoute(), [
                             'name'     => 'John Doe',
                             'email'    => 'invalid-email',
                             'password' => 'i-love-laravel',
                             'password_confirmation' => 'i-love-laravel',
                         ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');

        $this->assertCount(2, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_email_should_not_be_too_long()
    {
        $response = $this->post('/register', [
            'email' => str_repeat('a', 247).'@test.com', // 256
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_user_cannot_register_without_password()
    {
        $response = $this->from($this->getRoute())
                         ->post($this->postRoute(), [
                             'name'     => 'John Doe',
                             'email'    => 'john@example.com',
                             'password' => '',
                             'password_confirmation' => '',
                         ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('password');

        $this->assertCount(2, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_user_cannot_register_without_password_confirmation()
    {
        $response = $this->from($this->getRoute())
                         ->post($this->postRoute(), [
                             'name'     => 'John Doe',
                             'email'    => 'john@example.com',
                             'password' => 'i-love-laravel',
                             'password_confirmation' => '',
                         ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('password');

        $this->assertCount(2, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_user_cannot_register_with_passwords_not_matching()
    {
        $response = $this->from($this->getRoute())
                         ->post($this->postRoute(), [
                             'name'     => 'John Doe',
                             'email'    => 'john@example.com',
                             'password' => 'i-love-laravel',
                             'password_confirmation' => 'i-love-symfony',
                         ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('password');

        $this->assertCount(2, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
