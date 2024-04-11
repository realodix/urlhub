<?php

namespace Database\Factories;

use App\Models\{Url, Visit};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Visit>
 */
class VisitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Visit>
     */
    protected $model = Visit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url_id'         => Url::factory(),
            'visitor_id'     => 'foo_bar',
            'is_first_click' => true,
            'referer'        => 'https://github.com/realodix/urlhub',
        ];
    }
}
