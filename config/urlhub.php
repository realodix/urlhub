<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable to guest access
    |--------------------------------------------------------------------------
    */

    'allow_guest' => env('UH_ALLOWGUEST', true),

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable to register new users
    |--------------------------------------------------------------------------
    */

    'public_register' => env('UH_PUBLICREGISTER', true),

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
    | Hash Alphabet
    |--------------------------------------------------------------------------
    |
    | Characters to be used in generating unique keyword. A URL is composed
    | from a limited set of characters belonging to the US-ASCII character
    | set. These characters include digits (0-9), letters(A-Z, a-z), and
    | a few special characters ("-", ".", "_", "~").
    |
    | ASCII control characters (e.g. backspace, vertical tab, horizontal tab,
    | line feed etc), unsafe characters like space, \, <, >, {, } etc, and
    | any character outside the ASCII charset is not allowed to be placed
    | directly within URLs.
    |
    | Moreover, there are some characters that have special meaning within
    | URLs. These characters are called reserved characters. Some examples
    | of reserved characters are ?, /, #, : etc. Any data transmitted as
    | part of the URL, whether in query string or path segment, must not
    | contain these characters.
    |
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

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable to show shorten links statstics to Guest
    |--------------------------------------------------------------------------
    */

    'show_stat_to_guests' => env('UH_SHOW_STAT_TO_GUESTS', true),
];
