<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Number of characters in generating unique url_key
    |--------------------------------------------------------------------------
    |
    | If hash_size_1 is equal to hash_size_2, hash_size_2 is automatically
    | declared to be of no value.
    |
    */

    'hash_size_1' => env('HASH_SIZE_1', 6), // >= 1
    'hash_size_2' => env('HASH_SIZE_2', 7), // >= 0

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
    | https://developer.mozilla.org/en-US/docs/Web/HTTP/Redirections
    | https://redirectdetective.com/redirection-types.html
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
