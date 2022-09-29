<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class ConfirmPasswordTest extends TestCase
{
    /**
     * @test
     *
     * @group f-auth
     */
    public function userCanViewPasswordConfirm()
    {
        $response = $this->loginAsNonAdmin()->get(route('password.confirm'));

        $response
            ->assertSuccessful()
            ->assertViewIs('frontend.auth.passwords.confirm');
    }

    /**
     * @test
     *
     * @group f-auth
     */
    public function guestCantViewPasswordConfirm()
    {
        $response = $this->get(route('password.confirm'));

        $response->assertRedirect(route('login'));
    }
}
