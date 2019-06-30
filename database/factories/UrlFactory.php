<?php

use App\Url;
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
    $url = new Url();

    return [
        'user_id'    => mt_rand(0, 2),
        'long_url'   => 'https://github.com/realodix/urlhub',
        'meta_title' => 'URL Title',
        'url_key'    => $url->key_generator(),
        'is_custom'  => 0,
        'clicks'     => mt_rand(10000, 999999999),
        'ip'         => $faker->ipv4,
    ];
});
