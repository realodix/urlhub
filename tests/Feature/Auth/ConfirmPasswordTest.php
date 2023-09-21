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

        $response->assertSuccessful();
    }

    /**
     * Sejak https://github.com/realodix/urlhub/pull/895, test mengalami kegagalan dengan
     * mengembalikan pesan "The response is not a view".
     * - [fail] php artisan test / ./vendor/bin/phpunit
     * - [pass] php artisan test --parallel
     *
     * @group f-auth
     */
    public function testViewIs(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('password.confirm'));

        $response->assertViewIs('auth.confirm-password');
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
