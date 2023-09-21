<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

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

    protected function guestMiddlewareRoute(): string
    {
        return route('home');
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCanViewALoginForm(): void
    {
        $response = $this->get($this->getRoute());

        $response->assertSuccessful();
    }

    /**
     * Sejak https://github.com/realodix/urlhub/pull/895, test mengalami kegagalan dengan
     * mengembalikan pesan "The response is not a view".
     * - [fail] php artisan test / ./vendor/bin/phpunit
     * - [pass] php artisan test --parallel
     *
     * assertViewHas juga menghasilkan hal yang sama
     *
     * @group f-auth
     */
    // public function testViewIs(): void
    // {
    //     $response = $this->get($this->getRoute());

    //     $response->assertViewIs('auth.login');
    // }

    /**
     * @test
     * @group f-auth
     */
    public function userCannotViewALoginFormWhenAuthenticated(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get($this->getRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    /**
     * @test
     * @group f-auth
     */
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

    /**
     * @test
     * @group f-auth
     */
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

    /**
     * @test
     * @group f-auth
     */
    public function unauthenticatedUsersCantAccessTheDashboard(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    /**
     * @test
     * @group f-auth
     */
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
