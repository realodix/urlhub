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
            ->get(route('dashboard.about'));

        $response->assertOk();
    }

    #[PHPUnit\Test]
    public function auNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('dashboard.about'));

        $response->assertForbidden();
    }
}
