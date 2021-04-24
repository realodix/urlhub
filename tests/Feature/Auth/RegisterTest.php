<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    protected function successfulRegistrationRoute()
    {
        return route('dashboard');
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

    /**
     * @test
     * @group f-auth
     */
    public function userCanViewARegistrationForm()
    {
        $response = $this->get($this->getRoute());

        $response
            ->assertSuccessful()
            ->assertViewIs('frontend.auth.register');
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotViewARegistrationFormWhenAuthenticated()
    {
        $response = $this->loginAsNonAdmin()->get($this->getRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCanRegister()
    {
        Event::fake();

        $response = $this->post($this->postRoute(), [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $response->assertRedirect($this->successfulRegistrationRoute());
        $this->assertCount(2, User::all());
        $this->assertAuthenticatedAs($user = User::whereName('John Doe')->first());
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('i-love-laravel', $user->password));
        Event::assertDispatched(Registered::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    /**
     * @test
     * @group f-auth
     */
    public function nameShouldNotBeTooLong()
    {
        $response = $this->post('/register', [
            'name' => str_repeat('a', 51),
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors('name');
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotRegisterWithoutName()
    {
        $response =
            $this
                ->from($this->getRoute())
                ->post($this->postRoute(), [
                    'name'                  => '',
                    'email'                 => 'john@example.com',
                    'password'              => 'i-love-laravel',
                    'password_confirmation' => 'i-love-laravel',
                ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('name');

        $this->assertCount(1, User::all());
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotRegisterWithoutEmail()
    {
        $response =
        $this
            ->from($this->getRoute())
            ->post($this->postRoute(), [
                'name'                  => 'John Doe',
                'email'                 => '',
                'password'              => 'i-love-laravel',
                'password_confirmation' => 'i-love-laravel',
            ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');

        $this->assertCount(1, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotRegisterWithInvalidEmail()
    {
        $response =
            $this
                ->from($this->getRoute())
                ->post($this->postRoute(), [
                    'name'                  => 'John Doe',
                    'email'                 => 'invalid-email',
                    'password'              => 'i-love-laravel',
                    'password_confirmation' => 'i-love-laravel',
                ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');

        $this->assertCount(1, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     * @group f-auth
     */
    public function emailShouldNotBeTooLong()
    {
        $response = $this->post('/register', [
            'email' => str_repeat('a', 247).'@test.com', // 256
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotRegisterWithoutPassword()
    {
        $response =
            $this
                ->from($this->getRoute())
                ->post($this->postRoute(), [
                    'name'                  => 'John Doe',
                    'email'                 => 'john@example.com',
                    'password'              => '',
                    'password_confirmation' => '',
                ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('password');

        $this->assertCount(1, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotRegisterWithoutPasswordConfirmation()
    {
        $response =
            $this
                ->from($this->getRoute())
                ->post($this->postRoute(), [
                    'name'                  => 'John Doe',
                    'email'                 => 'john@example.com',
                    'password'              => 'i-love-laravel',
                    'password_confirmation' => '',
                ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('password');

        $this->assertCount(1, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotRegisterWithPasswordsNotMatching()
    {
        $response =
            $this
                ->from($this->getRoute())
                ->post($this->postRoute(), [
                    'name'                  => 'John Doe',
                    'email'                 => 'john@example.com',
                    'password'              => 'i-love-laravel',
                    'password_confirmation' => 'i-love-symfony',
                ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('password');

        $this->assertCount(1, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
