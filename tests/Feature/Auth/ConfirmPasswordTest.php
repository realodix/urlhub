<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class ConfirmPasswordTest extends TestCase
{
    /**
     * @test
     * @group f-auth
     */
    public function user_can_view_password_confirm()
    {
        $response = $this->loginAsNonAdmin()->get(route('password.confirm'));

        $response
            ->assertSuccessful()
            ->assertViewIs('frontend.auth.passwords.confirm');
    }

    /**
     * @test
     * @group f-auth
     */
    public function guest_cant_view_password_confirm()
    {
        $response = $this->get(route('password.confirm'));

        $response->assertRedirect(route('login'));
    }
}
