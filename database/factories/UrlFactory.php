<?php

namespace Database\Factories;

use App\Models\Url;
use App\Models\User;
use App\Services\KeyGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Url>
 */
class UrlFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Url>
     */
    protected $model = Url::class;

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
            'keyword'     => app(KeyGeneratorService::class)->generateRandomString(),
            'is_custom'   => false,
            'ip'          => fake()->ipv4(),
        ];
    }
}
