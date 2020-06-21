<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Unregistered Users Access
    |--------------------------------------------------------------------------
    */

    /**
     * If enabled, unregistered users can use UrlHub to shorten their URL
     * without having to register / login first.
     */
    'guest' => env('UH_GUEST', true),

    /**
     * If disabled, every unregistered users can't register.
     */
    'guest_register' => env('UH_GUEST_REGISTER', true),

    /**
     * Enable/Disable to allow unregistered users see shortened links
     * statistics.
     */
    'guest_show_stat' => env('UH_GUEST_SHOW_STAT', true),

    /*
    |--------------------------------------------------------------------------
    | URL Ending
    |--------------------------------------------------------------------------
    */

    /**
     * The expected (and maximum) number of characters in generating unique
     * keyword.
     */
    'hash_length' => env('HASH_LENGTH', 6), // >= 1

    /**
     * Characters to be used in generating unique keyword. For convenience,
     * currently the allowed characters are only alphanumeric consisting of
     * a limited set of characters belonging to the US-ASCII characters,
     * including digits (0-9), letters (A-Z, a-z).
     *
     * If you add non-alphanumeric characters, the method for calculating the
     * remaining keywords (/App/Url::keywordRemaining()) will not be optimal
     * or get worse.
     */
    'hash_char' => env(
        'HASH_CHAR',
        'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    ),

    /**
     * The HTTP redirect code, redirect for short, is a way to forward visitors
     * and search engines from one URL to another.
     *
     * Not all redirection is treated equally; the redirection instruction sent
     * to a browser can contain in its header the HTTP status:
     * - 301 (Moved Permanently)
     * - 302 (Found)
     * - 307 (Temporary Redirect)
     * - 308 (Permanent Redirect)
     *
     * You can read the references below to find out what code is good to use.
     * - https://developer.mozilla.org/en-US/docs/Web/HTTP/Redirections
     * - https://redirectdetective.com/redirection-types.html
     */
    'redirect_code' => env('UH_REDIRECT_CODE', 301),

    /**
     * List of non allowed domain.
     *
     * This list is used to prevent shortening of urls that contain one of the
     * domains below.
     */
    'domain_blacklist' => [
        config('app.url'),
        // 'bit.ly',
        // 'adf.ly',
        // 'goo.gl',
        // 't.co',
    ],

    /**
     * List of reserved URL ending.
     *
     * This keyword has a special meaning in UrlHub. Some of them represent
     * things that look like folder names in public folders. You are free
     * to add keywords that you want to prevent, for example rude words.
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
