<?php

namespace Database\Factories;

use App\Models\Url;
use App\Models\User;
use App\Services\KeyService;
use Illuminate\Database\Eloquent\Factories\Factory;

class UrlFactory extends Factory
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
        $keySrvc = new KeyService();

        return [
            'user_id'    => User::factory(),
            'long_url'   => 'https://github.com/realodix/urlhub',
            'meta_title' => 'No Title',
            'keyword'    => $keySrvc->randomString(),
            'is_custom'  => 0,
            'clicks'     => mt_rand(10000, 999999999),
            'ip'         => $this->faker->ipv4(),
        ];
    }
}
