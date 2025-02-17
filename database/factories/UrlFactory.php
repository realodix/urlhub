<?php

namespace Database\Factories;

use App\Enums\UserType;
use App\Models\Url;
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
            'user_id'     => User::factory(),
            'user_type'   => UserType::User,
            'destination' => 'https://github.com/realodix/urlhub',
            'title'       => 'No Title',
            'keyword'     => app(KeyGeneratorService::class)->randomString(),
            'forward_query' => true,
            'is_custom'   => false,
            'user_uid'    => fake()->uuid(),
        ];
    }

    public function guest(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => Url::GUEST_ID,
                'user_type' => UserType::Guest,
                'forward_query' => false,
            ];
        });
    }
}
