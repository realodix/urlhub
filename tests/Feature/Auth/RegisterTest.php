<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
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

    #[PHPUnit\Test]
    public function userCanViewARegistrationForm(): void
    {
        $response = $this->get($this->getRoute());

        $response->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function userCanSeeTheRegisterPage(): void
    {
        $response = $this->get($this->getRoute());

        $response->assertSuccessful()
            ->assertViewIs('auth.register');
    }

    #[PHPUnit\Test]
    public function userCannotViewARegistrationFormWhenAuthenticated(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get($this->getRoute());

        $response->assertRedirect(route('dashboard'));
    }

    #[PHPUnit\Test]
    public function userCanRegister(): void
    {
        Event::fake();

        $response = $this->post($this->postRoute(), [
            'name'     => 'John Doe',
            'email'    => 'john@example.com',
            'password' => 'i-love-laravel',
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
     * Test that a user cannot register with a name longer than the allowed limit.
     *
     * This test posts a registration request with a name consisting of 51 characters,
     * and asserts that the response has a status of 302, indicating a redirect, and
     * that the session contains an error for the 'name' field.
     */
    #[PHPUnit\Test]
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
     * Test that a user cannot register without providing a name.
     *
     * This test posts a registration request without providing a name, and asserts
     * that the response redirects to the registration page, that the session contains
     * an error for the 'name' field, and that the user is not logged in.
     */
    #[PHPUnit\Test]
    public function userCannotRegisterWithoutName(): void
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

        $this->assertCount(0, User::all());
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    #[PHPUnit\Test]
    public function userCannotRegisterWithoutEmail(): void
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

        $this->assertCount(0, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    #[PHPUnit\Test]
    public function userCannotRegisterWithInvalidEmail(): void
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

        $this->assertCount(0, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    #[PHPUnit\Test]
    public function emailShouldNotBeTooLong(): void
    {
        $response = $this->post('/register', [
            'email' => str_repeat('a', 247) . '@test.com', // 256
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    #[PHPUnit\Test]
    public function userCannotRegisterWithoutPassword(): void
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

        $this->assertCount(0, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    #[PHPUnit\Test]
    public function userCannotRegisterWithoutPasswordConfirmation(): void
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

        $this->assertCount(0, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    #[PHPUnit\Test]
    public function userCannotRegisterWithPasswordsNotMatching(): void
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

        $this->assertCount(0, User::all());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
