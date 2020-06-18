<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Guest Access
    |--------------------------------------------------------------------------
    */

    /**
     * If enabled, guests can use UrlHub to shorten their URL without having to
     * register / login first.
     */
    'guest' => env('UH_GUEST', true),

    /**
     * If disabled, everyone can't register.
     */
    'guest_register' => env('UH_GUEST_REGISTER', true),

    /**
     * Enable/Disable to show shorten links statstics to Guest.
     */
    'show_stat_to_guests' => env('UH_SHOW_STAT_TO_GUESTS', true),

    /*
    |--------------------------------------------------------------------------
    | Hash Length
    |--------------------------------------------------------------------------
    |
    | The expected (and maximum) number of characters in generating unique
    | keyword.
    |
    */

    'hash_length' => env('HASH_LENGTH', 6), // >= 1

    /*
    |--------------------------------------------------------------------------
    | Hash Character
    |--------------------------------------------------------------------------
    |
    | Characters to be used in generating unique keyword. For convenience,
    | currently the allowed characters are only alphanumeric consisting of
    | a limited set of characters belonging to the US-ASCII characters,
    | including digits (0-9), letters (A-Z, a-z).
    |
    | If you add non-alphanumeric characters, the method for calculating the
    | remaining keywords (/App/Url::keywordRemaining()) will not be optimal
    | or get worse.
    |
    */

    'hash_char' => env(
        'HASH_CHAR',
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

    'redirect_code' => env('UH_REDIRECT_CODE', 301),

    /*
    |--------------------------------------------------------------------------
    | List of non allowed domain
    |--------------------------------------------------------------------------
    |
    | This list is used to prevent shortening of urls that contain one of the
    | domains below.
    |
    */

    'domain_blacklist' => [
        config('app.url'),
        // 'bit.ly',
        // 'adf.ly',
        // 'goo.gl',
        // 't.co',
    ],

    /*
    |--------------------------------------------------------------------------
    | List of reserved URL ending
    |--------------------------------------------------------------------------
    |
    | This keyword has a special meaning in UrlHub. Some of them represent
    | things that look like folder names in public folders. You are free
    | to add keywords that you want to prevent, for example rude words.
    |
    */

    'reserved_keyword' => [
        'css',
        'images',
        'img',
        'fonts',
        'js',
        'svg',
    ],

];
