<?php

namespace Tests\Feature\Config;

use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('config')]
#[PHPUnit\Group('auth-page')]
class SettingsPageTest extends TestCase
{
    private function postRoute(): string
    {
        return route('dboard.settings.update');
    }

    public function test_settings_page(): void
    {
        $this->actingAs($this->adminUser())
            ->get(route('dboard.settings'))
            ->assertOk()
            ->assertViewIs('backend.settings');
    }

    public function test_settings_page_for_normal_user(): void
    {
        $this->actingAs($this->basicUser())
            ->get(route('dboard.settings'))
            ->assertForbidden();
    }

    #[PHPUnit\TestWith([1])]
    #[PHPUnit\TestWith([21])]
    public function test_validate_keyword_length($value): void
    {
        $data = ['keyword_length' => $value];
        $this->actingAs($this->adminUser())
            ->post($this->postRoute(), $this->formData($data))
            ->assertInvalid();
    }

    #[PHPUnit\TestWith([1])]
    #[PHPUnit\TestWith([20])]
    public function test_validate_custom_keyword_min_length($value): void
    {
        $data = ['custom_keyword_min_length' => $value];
        $this->actingAs($this->adminUser())
            ->post($this->postRoute(), $this->formData($data))
            ->assertInvalid();
    }

    #[PHPUnit\TestWith([2])]
    #[PHPUnit\TestWith([21])]
    public function test_validate_custom_keyword_max_length($value): void
    {
        $data = ['custom_keyword_max_length' => $value];
        $this->actingAs($this->adminUser())
            ->post($this->postRoute(), $this->formData($data))
            ->assertInvalid();
    }

    public function test_validForm(): void
    {
        $this->actingAs($this->adminUser())
            ->post($this->postRoute(), $this->formData())
            ->assertValid();
    }

    private function formData(?array $value = null): array
    {
        $data = [
            'anyone_can_shorten' => true,
            'anyone_can_register' => true,
            'keyword_length' => 7,
            'custom_keyword_min_length' => 5,
            'custom_keyword_max_length' => 10,
            'retrieve_web_title' => true,
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
