<?php

namespace Tests\Feature\AuthPage;

use PHPUnit\Framework\Attributes\{Group, Test};
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    #[Test]
    #[Group('f-about')]
    public function auAdminCanAccessThisPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dashboard.about'));

        $response->assertOk();
    }

    #[Test]
    #[Group('f-about')]
    public function auNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('dashboard.about'));

        $response->assertForbidden();
    }
}
