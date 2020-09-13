<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function getValidToken($user)
    {
        return Password::broker()->createToken($user);
    }

    protected function getInvalidToken()
    {
        return 'invalid-token';
    }

    protected function getRoute($token)
    {
        return route('password.reset', $token);
    }

    protected function postRoute()
    {
        return '/password/reset';
    }

    protected function successfulRoute()
    {
        return route('dashboard');
    }

    /**
     * @test
     * @group f-auth
     */
    public function user_can_view_a_password_reset_form()
    {
        $response = $this->get($this->getRoute($token = $this->getValidToken($this->nonAdmin())));

        $response
            ->assertSuccessful()
            ->assertViewIs('frontend.auth.passwords.reset')
            ->assertViewHas('token', $token);
    }

    /**
     * @test
     * @group f-auth
     */
    public function user_can_reset_password_with_valid_token()
    {
        Event::fake();

        $user = $this->nonAdmin();

        $response = $this->post($this->postRoute(), [
            'token'                 => $this->getValidToken($user),
            'email'                 => $user->email,
            'password'              => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);

        $response->assertRedirect($this->successfulRoute());
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));
        $this->assertAuthenticatedAs($user);
        Event::assertDispatched(PasswordReset::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    /**
     * @test
     * @group f-auth
     */
    public function user_cannot_reset_password_with_invalid_token()
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response =
            $this
                ->from($this->getRoute($this->getInvalidToken()))
                ->post($this->postRoute(), [
                    'token'                 => $this->getInvalidToken(),
                    'email'                 => $user->email,
                    'password'              => 'new-awesome-password',
                    'password_confirmation' => 'new-awesome-password',
                ]);

        $response->assertRedirect($this->getRoute($this->getInvalidToken()));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    /**
     * @test
     * @group f-auth
     */
    public function user_cannot_reset_password_without_providing_a_new_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response =
            $this
                ->from($this->getRoute($token = $this->getValidToken($user)))
                ->post($this->postRoute(), [
                    'token'                 => $token,
                    'email'                 => $user->email,
                    'password'              => '',
                    'password_confirmation' => '',
                ]);

        $response
            ->assertRedirect($this->getRoute($token))
            ->assertSessionHasErrors('password');

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    /**
     * @test
     * @group f-auth
     */
    public function user_cannot_reset_password_without_providing_an_email()
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response =
            $this
                ->from($this->getRoute($token = $this->getValidToken($user)))
                ->post($this->postRoute(), [
                    'token'                 => $token,
                    'email'                 => '',
                    'password'              => 'new-awesome-password',
                    'password_confirmation' => 'new-awesome-password',
                ]);

        $response
            ->assertRedirect($this->getRoute($token))
            ->assertSessionHasErrors('email');

        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }
}
