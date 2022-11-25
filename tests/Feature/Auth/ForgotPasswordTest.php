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
    protected function requestRoute()
    {
        return route('password.request');
    }

    protected function getRoute()
    {
        return route('password.email');
    }

    protected function postRoute()
    {
        return route('password.email');
    }

    /**
     * @test
     * @group f-auth
     */
    public function userCanViewAnEmailPasswordForm()
    {
        $response = $this->get($this->requestRoute());

        $response
            ->assertSuccessful()
            ->assertViewIs('auth.forgot-password');
    }

    /**
     * @test
     * @group f-auth
     */
    public function userReceivesAnEmailWithAPasswordResetLink()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $this->post($this->postRoute(), [
            'email' => 'john@example.com',
        ]);
        $this->assertNotNull($token = DB::table('password_resets')->first());

        Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    /**
     * @test
     * @group f-auth
     */
    public function userDoesNotReceiveEmailWhenNotRegistered()
    {
        Notification::fake();

        $response = $this->from($this->getRoute())->post($this->postRoute(), [
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
    public function emailIsRequired()
    {
        $response = $this->from($this->getRoute())->post($this->postRoute(), []);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');
    }

    /**
     * @test
     * @group f-api
     */
    public function emailIsAValidEmail()
    {
        $response = $this->from($this->getRoute())->post($this->postRoute(), [
            'email' => 'invalid-email',
        ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');
    }
}
