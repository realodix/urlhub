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

$factory->define(App\UrlStat::class, function (Faker $faker) {
    return [
        'url_id'           => mt_rand(0, 50),
        'referer'          => 'https://github.com/realodix/urlhub',
        'ip'               => $faker->ipv4,
        'device'           => 'WebKit',
        'platform'         => 'Windows',
        'platform_version' => '10',
        'browser'          => 'Chrome',
        'browser_version'  => '75.0.3770.100',
    ];
});
