<?php

namespace Tests\Feature\AuthPage;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    /**
     * @test
     * @group f-about
     */
    public function auAdminCanAccessThisPage(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dashboard.about'));

        $response->assertOk();
    }

    /**
     * @test
     * @group f-about
     */
    public function auNormalUserCantAccessThisPage(): void
    {
        $response = $this->actingAs($this->normalUser())
            ->get(route('dashboard.about'));

        $response->assertForbidden();
    }
}
