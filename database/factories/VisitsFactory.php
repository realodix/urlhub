<?php

use App\Models\Url;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

$factory->define(App\Models\Visit::class, function (Faker $faker) {
    return [
        'url_id' => function () {
            return Url::factory()->create()->id;
        },
        'referer'          => 'https://github.com/realodix/urlhub',
        'ip'               => $this->faker->ipv4,
        'device'           => 'WebKit',
        'platform'         => 'Windows',
        'platform_version' => '10',
        'browser'          => 'Chrome',
        'browser_version'  => '75.0.3770.100',
    ];
});
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Url::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url_id' => function () {
                return Url::factory()->create()->id;
            },
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
