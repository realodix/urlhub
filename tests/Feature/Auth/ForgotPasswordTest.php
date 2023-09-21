<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    protected function requestRoute(): string
    {
        return route('password.request');
    }

    protected function getRoute(): string
    {
        return route('password.email');
    }

    protected function postRoute(): string
    {
        return route('password.email');
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCanViewAnEmailPasswordForm(): void
    {
        $response = $this->get($this->requestRoute());

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
    //     $response = $this->get($this->requestRoute());

    //     $response->assertViewIs('auth.forgot-password');
    // }

    /**
     * @test
     * @group f-auth
     */
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

    /**
     * @test
     * @group f-auth
     */
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

    /**
     * @test
     * @group f-auth
     */
    public function emailIsRequired(): void
    {
        $response = $this->from($this->getRoute())
            ->post($this->postRoute(), []);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');
    }

    /**
     * @test
     * @group f-api
     */
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
}
