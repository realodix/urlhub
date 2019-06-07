<?php

namespace Tests\Feature;

use App\Url;
use Tests\TestCase;

class UrlBeTest extends TestCase
{
    /**
     * Dashboard Page
     */

    /** @test */
    public function can_access_dashboard_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
    }


    /**
     * All URLs Page
     */

     /** @test */
    public function can_access_allurl_page()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('dashboard.allurl'));
        $response->assertStatus(200);
    }
}
