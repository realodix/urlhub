<?php

namespace Tests\Feature\AuthPage;

use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
class AboutPageTest extends TestCase
{
    #[PHPUnit\Test]
    public function auAdminCanAccessThisPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dboard.about'));

        $response->assertOk();
    }

    #[PHPUnit\Test]
    public function auNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('dboard.about'));

        $response->assertForbidden();
    }
}
