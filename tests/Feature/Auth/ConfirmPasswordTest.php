<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class ConfirmPasswordTest extends TestCase
{
    /**
     * @test
     * @group f-auth
     */
    public function userCanViewPasswordConfirm()
    {
        $response = $this->actingAs($this->nonAdmin())
            ->get(route('password.confirm'));

        $response
            ->assertSuccessful()
            ->assertViewIs('auth.confirm-password');
    }

    /**
     * @test
     * @group f-auth
     */
    public function guestCantViewPasswordConfirm()
    {
        $response = $this->get(route('password.confirm'));

        $response->assertRedirectToRoute('login');
    }
}
