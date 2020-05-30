<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hash Length
    |--------------------------------------------------------------------------
    |
    | The expected (and maximum) number of characters in generating unique
    | url_key.
    |
    */

    'hash_length' => env('HASH_LENGTH', 6), // >= 1

    /*
    |--------------------------------------------------------------------------
    | Characters to be used in generating unique url_key
    |--------------------------------------------------------------------------
    */

    'hash_alphabet' => env(
        'HASH_ALPHABET',
        '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ),

    /*
    |--------------------------------------------------------------------------
    | URL Redirection Status Code
    |--------------------------------------------------------------------------
    |
    | The HTTP redirect code, redirect for short, is a way to forward visitors
    | and search engines from one URL to another.
    |
    | You can read the references below to find out what code is good to use.
    | - https://developer.mozilla.org/en-US/docs/Web/HTTP/Redirections
    | - https://redirectdetective.com/redirection-types.html
    |
    */

    'redirection_code' => env('URLHUB_REDIRECTION_CODE', 301),

    /*
    |--------------------------------------------------------------------------
    | A list of non allowed domain
    |--------------------------------------------------------------------------
    */

    'blacklist' => [
        config('app.url'),
        // 'bit.ly',
        // 'adf.ly',
        // 'goo.gl',
        // 't.co',
    ],

    /*
    |--------------------------------------------------------------------------
    | List of ending that prohibited
    |--------------------------------------------------------------------------
    */

    'prohibited_ending' => [
        'css',
        'images',
        'img',
        'fonts',
        'js',
        'svg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable to guest access
    |--------------------------------------------------------------------------
    */

    'allow_guest' => env('URLHUB_ALLOWGUEST', true),

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable to register new users
    |--------------------------------------------------------------------------
    */

    'public_register' => env('URLHUB_PUBLICREGISTER', true),
];
