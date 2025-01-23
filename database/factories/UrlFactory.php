<?php

namespace Database\Factories;

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
            'destination' => 'https://github.com/realodix/urlhub',
            'title'       => 'No Title',
            'keyword'     => app(KeyGeneratorService::class)->randomString(),
            'is_custom'   => false,
            'user_sign'   => fake()->uuid(),
        ];
    }
}
