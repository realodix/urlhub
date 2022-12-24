<?php

namespace Database\Factories;

use App\Models\Url;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
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
     * @return array
     */
    public function definition()
    {
        return [
            'url_id'     => Url::factory(),
            'visitor_id' => 'foo_bar',
            'is_first_click' => true,
            'referer'    => 'https://github.com/realodix/urlhub',
            'ip'         => $this->faker->ipv4(),
            'browser'    => 'Firefox',
            'browser_version' => '108',
            'device'     => 'Desktop',
            'os'         => 'Windows',
            'os_version' => '11',
        ];
    }
}
