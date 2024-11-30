<?php

namespace Tests\Feature\Auth;

use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
class ConfirmPasswordTest extends TestCase
{
    #[PHPUnit\Test]
    public function userCanViewPasswordConfirm(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('password.confirm'));

        $response->assertSuccessful();
    }

    #[PHPUnit\Test]
    public function userCanSeeThePasswordConfirmationPage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('password.confirm'));

        $response->assertSuccessful()
            ->assertViewIs('auth.confirm-password');
    }

    #[PHPUnit\Test]
    public function guestCantViewPasswordConfirm(): void
    {
        $response = $this->get(route('password.confirm'));
        $response->assertRedirectToRoute('login');
    }
}
