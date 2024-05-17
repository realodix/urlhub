<?php

namespace Tests\Feature\AuthPage;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('auth-page')]
class AboutPageTest extends TestCase
{
    #[Test]
    public function auAdminCanAccessThisPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dashboard.about'));

        $response->assertOk();
    }

    #[Test]
    public function auNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('dashboard.about'));

        $response->assertForbidden();
    }
}
