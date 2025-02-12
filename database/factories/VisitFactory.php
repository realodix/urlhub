<?php

namespace Database\Factories;

use App\Enums\UserType;
use App\Models\Url;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url_id'         => Url::factory(),
            'user_type'      => UserType::User,
            'user_uid'       => fake()->uuid(),
            'is_first_click' => true,
            'referer'        => 'https://github.com/realodix/urlhub',
        ];
    }

    public function guest(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => UserType::Guest,
            ];
        });
    }
}
