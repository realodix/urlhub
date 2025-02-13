<?php

namespace Tests\Unit\Controllers;

use App\Models\Url;
use App\Models\Visit;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\Group('controller')]
class RedirectControllerTest extends TestCase
{
    public function testUrlRedirection(): void
    {
        $url = Url::factory()->create();
        $settings = app(\App\Settings\GeneralSettings::class);

        $response = $this->get(route('home') . '/' . $url->keyword);
        $response->assertRedirect($url->destination)
            ->assertStatus($settings->redirect_status_code);

        $this->assertCount(1, Visit::all());
    }
}
