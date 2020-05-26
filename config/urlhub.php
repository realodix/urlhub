<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Number of characters in generating unique url_key
    |--------------------------------------------------------------------------
    */

    'hash_size_1' => env('HASH_SIZE_1', 6), // >= 1

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
    | URL Redirection Types
    |--------------------------------------------------------------------------
    |
    | https://developer.mozilla.org/en-US/docs/Web/HTTP/Redirections
    | https://redirectdetective.com/redirection-types.html
    |
    */

    'redirection_type' => env('URLHUB_REDIRECTION_TYPE', 301),

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
