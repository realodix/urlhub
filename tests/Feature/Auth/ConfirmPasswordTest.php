<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class ConfirmPasswordTest extends TestCase
{
    /**
     * @test
     * @group f-auth
     */
    public function userCanViewPasswordConfirm(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('password.confirm'));

        $response
            ->assertSuccessful()
            ->assertViewIs('auth.confirm-password');
    }

    /**
     * @test
     * @group f-auth
     */
    public function guestCantViewPasswordConfirm(): void
    {
        $response = $this->get(route('password.confirm'));
        $response->assertRedirectToRoute('login');
    }
}
