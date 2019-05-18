<?php

namespace Tests\Feature\Auth;

use App\User;
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

    protected function passwordResetGetRoute($token)
    {
        return route('password.reset', $token);
    }

    protected function passwordResetPostRoute()
    {
        return '/password/reset';
    }

    protected function successfulPasswordResetRoute()
    {
        return route('home');
    }

    protected function guestMiddlewareRoute()
    {
        return route('home');
    }

    public function test_user_can_view_a_password_reset_form()
    {
        $user = factory(User::class)->create();

        $response = $this->get($this->passwordResetGetRoute($token = $this->getValidToken($user)));

        $response->assertSuccessful();
        $response->assertViewIs('frontend.auth.passwords.reset');
        $response->assertViewHas('token', $token);
    }

    public function test_user_cannot_view_a_password_reset_form_when_authenticated()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get($this->passwordResetGetRoute($this->getValidToken($user)));

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function test_user_can_reset_password_with_valid_token()
    {
        Event::fake();

        $user = factory(User::class)->create();

        $response = $this->post($this->passwordResetPostRoute(), [
            'token' => $this->getValidToken($user),
            'email' => $user->email,
            'password' => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);

        $response->assertRedirect($this->successfulPasswordResetRoute());
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));
        $this->assertAuthenticatedAs($user);
        Event::assertDispatched(PasswordReset::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    public function test_user_cannot_reset_password_with_invalid_token()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->from($this->passwordResetGetRoute($this->getInvalidToken()))->post($this->passwordResetPostRoute(), [
            'token' => $this->getInvalidToken(),
            'email' => $user->email,
            'password' => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);

        $response->assertRedirect($this->passwordResetGetRoute($this->getInvalidToken()));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    public function test_user_cannot_reset_password_without_providing_a_new_password()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->from($this->passwordResetGetRoute($token = $this->getValidToken($user)))->post($this->passwordResetPostRoute(), [
            'token' => $token,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect($this->passwordResetGetRoute($token));
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    public function test_user_cannot_reset_password_without_providing_an_email()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->from($this->passwordResetGetRoute($token = $this->getValidToken($user)))->post($this->passwordResetPostRoute(), [
            'token' => $token,
            'email' => '',
            'password' => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);

        $response->assertRedirect($this->passwordResetGetRoute($token));
        $response->assertSessionHasErrors('email');
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }
}
