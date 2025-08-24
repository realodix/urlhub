<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\{DB, Hash, Notification, Password};
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
class ForgotPasswordTest extends TestCase
{
    #[PHPUnit\Test]
    public function userCanViewAnEmailPasswordForm(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function userCanSeeTheForgotPasswordPage(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertSuccessful()
            ->assertViewIs('auth.forgot-password');
    }

    #[PHPUnit\Test]
    public function userReceivesAnEmailWithAPasswordResetLink(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $this->from(route('password.request'))
            ->post(route('password.email'), [
                'email' => 'john@example.com',
            ]);

        $token = DB::table('password_reset_tokens')->first();
        $this->assertNotNull($token);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    #[PHPUnit\Test]
    public function userDoesNotReceiveEmailWhenNotRegistered(): void
    {
        Notification::fake();

        $response = $this->from(route('password.request'))
            ->post(route('password.email'), [
                'email' => 'nobody@example.com',
            ]);

        $response
            ->assertRedirect(route('password.request'))
            ->assertSessionHasErrors('email');

        Notification::assertNotSentTo(User::factory()->make(['email' => 'nobody@example.com']), ResetPassword::class);
    }

    #[PHPUnit\Test]
    public function emailIsRequired(): void
    {
        $response = $this->from(route('password.request'))
            ->post(route('password.email'), []);

        $response
            ->assertRedirect(route('password.request'))
            ->assertSessionHasErrors('email');
    }

    #[PHPUnit\Test]
    public function emailIsAValidEmail(): void
    {
        $response = $this->from(route('password.request'))
            ->post(route('password.email'), [
                'email' => 'invalid-email',
            ]);

        $response
            ->assertRedirect(route('password.request'))
            ->assertSessionHasErrors('email');
    }

    #[PHPUnit\Test]
    public function userCanResetPassword()
    {
        $email = 'test@example.com';
        $newPassword = 'newPassword';
        $user = User::factory()->create(['email' => $email]);

        // Send password reset link
        $this->post('/forgot-password', ['email' => $email]);

        // Reset password
        $response = $this->post(route('password.update'), [
            'token' => Password::createToken($user),
            'email' => $email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        // Check if password was reset
        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
    }
}
