<?php

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
        'user_id'           => $faker->biasedNumberBetween($min = 0, $max = 2, $function = 'sqrt'),
        'long_url'          => $faker->url,
        'long_url_title'    => $faker->numerify('URL Title ###'),
        'short_url'         => $faker->regexify('[a-zA-Z0-9]{6}'),
        'short_url_custom'  => 0,
        'views'             => $faker->biasedNumberBetween($min = 0, $max = 1000, $function = 'sqrt'),
        'ip'                => $faker->localIpv4,
    ];
});
