<?php

namespace Database\Factories;

use App\Enums\UserType;
use App\Models\User;
use App\Services\KeyGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Url>
 */
class UrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'user_type' => UserType::User,
            'destination' => 'https://github.com/realodix/urlhub',
            'dest_android' => 'https://play.google.com/store/apps/details?id=com.canva.editor',
            'dest_ios' => 'https://apps.apple.com/us/app/canva-ai-photo-video-editor/id897446215',
            'title' => 'No Title',
            'keyword' => app(KeyGeneratorService::class)->randomString(),
            'forward_query' => true,
            'is_custom' => false,
            'user_uid' => fake()->uuid(),
        ];
    }

    public function guest(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => User::GUEST_ID,
                'user_type' => UserType::Guest,
                'forward_query' => false,
            ];
        });
    }
}
