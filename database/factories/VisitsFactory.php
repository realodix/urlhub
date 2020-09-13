<?php

namespace Database\Factories;

use App\Models\Url;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
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
            'url_id'           => Url::factory(),
            'referer'          => 'https://github.com/realodix/urlhub',
            'ip'               => $this->faker->ipv4,
            'device'           => 'WebKit',
            'platform'         => 'Windows',
            'platform_version' => '10',
            'browser'          => 'Chrome',
            'browser_version'  => '75.0.3770.100',
        ];
    }
}
