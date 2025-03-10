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
    private function requestRoute(): string
    {
        return route('password.request');
    }

    private function getRoute(): string
    {
        return route('password.email');
    }

    private function postRoute(): string
    {
        return route('password.email');
    }

    #[PHPUnit\Test]
    public function userCanViewAnEmailPasswordForm(): void
    {
        $response = $this->get($this->requestRoute());

        $response->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function userCanSeeTheForgotPasswordPage(): void
    {
        $response = $this->get($this->requestRoute());

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

        $this->post($this->postRoute(), [
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

        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), [
                'email' => 'nobody@example.com',
            ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');

        Notification::assertNotSentTo(User::factory()->make(['email' => 'nobody@example.com']), ResetPassword::class);
    }

    #[PHPUnit\Test]
    public function emailIsRequired(): void
    {
        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), []);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');
    }

    #[PHPUnit\Test]
    public function emailIsAValidEmail(): void
    {
        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), [
                'email' => 'invalid-email',
            ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');
    }

    public function test_user_can_reset_password()
    {
        $email = 'test@example.com';
        $newPassword = 'newPassword';
        $user = User::factory()->create(['email' => $email]);

        // Send password reset link
        $this->post('/forgot-password', ['email' => $email]);

        // Reset password
        $response = $this->post('/reset-password', [
            'token' => Password::createToken($user),
            'email' => $email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        // Check if password was reset
        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
    }
}
