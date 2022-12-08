<?php

namespace Database\Factories;

use App\Models\Url;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     * @return array
     */
    public function definition()
    {
        return [
            'url_id'  => Url::factory(),
            'referer' => 'https://github.com/realodix/urlhub',
            'ip'      => $this->faker->ipv4(),
        ];
    }
}
