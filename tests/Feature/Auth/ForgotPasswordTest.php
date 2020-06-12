<?php

namespace Tests\Feature\Auth;

use App\User;
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
    public function user_can_view_an_email_password_form()
    {
        $response = $this->get($this->requestRoute());

        $response
            ->assertSuccessful()
            ->assertViewIs('frontend.auth.passwords.email');
    }

    /**
     * @test
     * @group f-auth
     */
    public function user_receives_an_email_with_a_password_reset_link()
    {
        Notification::fake();

        $user = factory(User::class)->create([
            'email' => 'john@example.com',
        ]);

        $response = $this->post($this->postRoute(), [
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
    public function user_does_not_receive_email_when_not_registered()
    {
        Notification::fake();

        $response = $this->from($this->getRoute())->post($this->postRoute(), [
            'email' => 'nobody@example.com',
        ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');
        Notification::assertNotSentTo(factory(User::class)->make(['email' => 'nobody@example.com']), ResetPassword::class);
    }

    /**
     * @test
     * @group f-auth
     */
    public function email_is_required()
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
    public function email_is_a_valid_email()
    {
        $response = $this->from($this->getRoute())->post($this->postRoute(), [
            'email' => 'invalid-email',
        ]);

        $response
            ->assertRedirect($this->getRoute())
            ->assertSessionHasErrors('email');
    }
}
