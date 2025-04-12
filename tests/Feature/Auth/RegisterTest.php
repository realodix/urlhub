<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\{Event, Hash};
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
class RegisterTest extends TestCase
{
    private function successfulRegistrationRoute(): string
    {
        return route('home');
    }

    private function getRoute(): string
    {
        return route('register');
    }

    private function postRoute(): string
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
            'name'     => 'usernametest',
            'email'    => 'email@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $user = User::where('name', 'usernametest')->first();

        $response->assertRedirect($this->successfulRegistrationRoute());
        $this->assertCount(1, User::all());
        $this->assertAuthenticatedAs($user);
        $this->assertEquals('usernametest', $user->name);
        $this->assertEquals('email@example.com', $user->email);
        $this->assertTrue(Hash::check('i-love-laravel', $user->password));

        Event::assertDispatched(Registered::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    #[PHPUnit\Test]
    public function usernameMustBeUnique(): void
    {
        $user = User::factory()->create(['name' => 'test']);
        $response = $this->post('/register', [
            'name'     => $user->name,
            'email'    => 'john@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors('name');
        $this->assertCount(1, User::all()); // 1 ($user)
    }

    #[PHPUnit\Test]
    public function storeUsernameAndEmailAsLowerCase(): void
    {
        $this->post('/register', [
            'name'     => 'Test',
            'email'    => 'John@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $this->assertSame('test', User::first()->name);
        $this->assertSame('john@example.com', User::first()->email);
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
                'name'     => 'usernametest',
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
                'name'     => 'usernametest',
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
            'email' => str_repeat('a', 247).'@test.com', // 256
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    #[PHPUnit\Test]
    public function userCannotRegisterWithoutPassword(): void
    {
        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), [
                'name'     => 'usernametest',
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
                'name'     => 'usernametest',
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
                'name'     => 'usernametest',
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
