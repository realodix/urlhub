<?php

namespace Tests\Feature\Config;

use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('config')]
#[PHPUnit\Group('auth-page')]
class SettingsPageTest extends TestCase
{
    #[PHPUnit\Test]
    public function access_Page_Admin_WillBeOk(): void
    {
        $this->actingAs($this->adminUser())
            ->get(route('dboard.settings'))
            ->assertOk()
            ->assertViewIs('backend.settings');
    }

    #[PHPUnit\Test]
    public function access_Page_BasicUser_WillBeForbidden(): void
    {
        $this->actingAs($this->basicUser())
            ->get(route('dboard.settings'))
            ->assertForbidden();
    }

    #[PHPUnit\TestWith([1])]
    #[PHPUnit\TestWith([21])]
    #[PHPUnit\Test]
    public function validate_keyword_length($value): void
    {
        $data = ['keyword_length' => $value];
        $this->actingAs($this->adminUser())
            ->post(route('dboard.settings.update'), $this->formData($data))
            ->assertInvalid();
    }

    #[PHPUnit\TestWith([1])]
    #[PHPUnit\TestWith([30])]
    #[PHPUnit\Test]
    public function validate_custom_keyword_min_length($value): void
    {
        $data = ['custom_keyword_min_length' => $value];
        $this->actingAs($this->adminUser())
            ->post(route('dboard.settings.update'), $this->formData($data))
            ->assertInvalid();
    }

    #[PHPUnit\TestWith([2])]
    #[PHPUnit\TestWith([31])]
    #[PHPUnit\Test]
    public function validate_custom_keyword_max_length($value): void
    {
        $data = ['custom_keyword_max_length' => $value];
        $this->actingAs($this->adminUser())
            ->post(route('dboard.settings.update'), $this->formData($data))
            ->assertInvalid();
    }

    public function test_validForm(): void
    {
        $this->actingAs($this->adminUser())
            ->post(route('dboard.settings.update'), $this->formData())
            ->assertValid();
    }

    private function formData(?array $value = null): array
    {
        $data = [
            'public_shortening' => true,
            'public_registration' => true,
            'keyword_length' => 7,
            'custom_keyword_min_length' => 5,
            'custom_keyword_max_length' => 10,
            'autofill_link_title' => true,
            'favicon_provider' => 'duckduckgo',
            'forward_query' => true,
            'redirect_cache_max_age' => 1,
            'track_bot_visits' => true,
        ];

        if ($value === null) {
            return $data;
        }

        return array_merge($data, $value);
    }
}
