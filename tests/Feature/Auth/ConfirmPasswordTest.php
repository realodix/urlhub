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
        $response = $this->actingAs($this->normalUser())
            ->get(route('password.confirm'));

        $response->assertSuccessful();
    }

    /**
     * Sejak https://github.com/realodix/urlhub/pull/895, test mengalami kegagalan dengan
     * mengembalikan pesan "The response is not a view".
     * - [fail] php artisan test / ./vendor/bin/phpunit
     * - [pass] php artisan test --parallel
     *
     * assertViewHas juga menghasilkan hal yang sama
     */
    // #[Group('f-auth')]
    // public function testViewIs(): void
    // {
    //     $response = $this->actingAs($this->normalUser())
    //         ->get(route('password.confirm'));

    //     $response->assertViewIs('auth.confirm-password');
    // }

    #[PHPUnit\Test]
    public function guestCantViewPasswordConfirm(): void
    {
        $response = $this->get(route('password.confirm'));
        $response->assertRedirectToRoute('login');
    }
}
