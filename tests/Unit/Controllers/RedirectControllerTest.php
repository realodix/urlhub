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

        $response = $this->get(route('home') . '/' . $url->keyword);
        $response->assertRedirect($url->destination)
            ->assertStatus(settings()->redirect_status_code);

        $this->assertCount(1, Visit::all());
    }

    /**
     * Visitors are redirected to destinations with source query parameters
     */
    public function testRedirectWithSourceQuery(): void
    {
        $url = Url::factory()->create(['destination' => 'https://example.com']);

        $response = $this->get(route('home') . '/' . $url->keyword . '?a=1&b=2');
        $response->assertRedirect($url->destination . '?a=1&b=2')
            ->assertStatus(settings()->redirect_status_code);
    }

    /**
     * Visitors are redirected to destinations without source query parameters
     * if the setting is set to false
     */
    public function testRedirectWithoutSourceQueryWhenSettingSetToFalse(): void
    {
        $setting = app(\App\Settings\GeneralSettings::class);
        $setting->fill(['forward_query' => false])->save();

        $url = Url::factory()->create(['destination' => 'https://example.com']);

        $response = $this->get(route('home') . '/' . $url->keyword . '?a=1&b=2');
        $response->assertRedirect($url->destination)
            ->assertStatus($setting->redirect_status_code);
    }
}
