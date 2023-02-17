<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    protected function successfulRegistrationRoute(): string
    {
        return route('home');
    }

    protected function getRoute(): string
    {
        return route('register');
    }

    protected function postRoute(): string
    {
        return route('register');
    }

    protected function guestMiddlewareRoute(): string
    {
        return route('home');
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCanViewARegistrationForm(): void
    {
        $response = $this->get($this->getRoute());

        $response
            ->assertSuccessful()
            ->assertViewIs('auth.register');
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotViewARegistrationFormWhenAuthenticated(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get($this->getRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCanRegister(): void
    {
        Event::fake();

        $response = $this->post($this->postRoute(), [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $user = User::whereName('John Doe')->first();

        $response->assertRedirect($this->successfulRegistrationRoute());
        $this->assertCount(1, User::all());
        $this->assertAuthenticatedAs($user);
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
    public function nameShouldNotBeTooLong(): void
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
    public function userCannotRegisterWithoutName(): void
    {
        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), [
                'name'                  => '',
                'email'                 => 'john@example.com',
                'password'              => 'i-love-laravel',
                'password_confirmation' => 'i-love-laravel',
            ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('name');

        $this->assertCount(0, User::all());
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotRegisterWithoutEmail(): void
    {
        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), [
                'name'                  => 'John Doe',
                'email'                 => '',
                'password'              => 'i-love-laravel',
                'password_confirmation' => 'i-love-laravel',
            ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');

        $this->assertCount(0, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotRegisterWithInvalidEmail(): void
    {
        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), [
                'name'                  => 'John Doe',
                'email'                 => 'invalid-email',
                'password'              => 'i-love-laravel',
                'password_confirmation' => 'i-love-laravel',
            ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');

        $this->assertCount(0, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     * @group f-auth
     */
    public function emailShouldNotBeTooLong(): void
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
    public function userCannotRegisterWithoutPassword(): void
    {
        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), [
                'name'                  => 'John Doe',
                'email'                 => 'john@example.com',
                'password'              => '',
                'password_confirmation' => '',
            ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('password');

        $this->assertCount(0, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotRegisterWithoutPasswordConfirmation(): void
    {
        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), [
                'name'                  => 'John Doe',
                'email'                 => 'john@example.com',
                'password'              => 'i-love-laravel',
                'password_confirmation' => '',
            ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('password');

        $this->assertCount(0, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotRegisterWithPasswordsNotMatching(): void
    {
        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), [
                'name'                  => 'John Doe',
                'email'                 => 'john@example.com',
                'password'              => 'i-love-laravel',
                'password_confirmation' => 'i-love-symfony',
            ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('password');

        $this->assertCount(0, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
