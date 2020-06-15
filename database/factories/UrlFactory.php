<?php

use App\Url;
use App\User;
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

$factory->define(Url::class, function (Faker $faker) {
    $url = new Url();

    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'long_url'   => 'https://github.com/realodix/urlhub',
        'meta_title' => 'No Title',
        'keyword'    => $url->keyGenerator(),
        'is_custom'  => 0,
        'clicks'     => mt_rand(10000, 999999999),
        'ip'         => $faker->ipv4,
    ];
});
