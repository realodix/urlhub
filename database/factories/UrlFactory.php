<?php

use Facades\App\Helpers\UrlHlp;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Url::class, function (Faker $faker) {
    return [
        'user_id'          => $faker->biasedNumberBetween($min = 0, $max = 20, $function = 'sqrt'),
        'long_url'         => 'https://github.com/realodix/plur',
        'meta_title'       => 'URL Title',
        'short_url'        => UrlHlp::link_generator(),
        'short_url_custom' => '',
        'views'            => $faker->biasedNumberBetween($min = 10000, $max = 999999999, $function = 'sqrt'),
        'ip'               => $faker->ipv4,
    ];
});
