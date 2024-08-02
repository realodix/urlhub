<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Unregistered Users Access
    |--------------------------------------------------------------------------
    */

    /*
     * If you set false, users must be registered to create Short URL.
     */
    'public_site' => true,

    /*
     * Enable users registration. If disabled it, no one can register.
     */
    'registration' => true,

    /*
    |--------------------------------------------------------------------------
    | Shorten URL
    |--------------------------------------------------------------------------
    */

    /*
     * The expected length of the keyword generated when creating a new short URL.
     */
    'keyword_length' => 5, // >= 1

    /*
     * Minimum length of custom keyword.
     */
    'custom_keyword_min_length' => 3,

    /*
     * Maximum length of custom keyword.
     */
    'custom_keyword_max_length' => 11,

    /*
     * List of non allowed domain
     *
     * This list is used to prevent shortening of urls that contain one of the
     * domains below.
     */
    'domain_blacklist' => [
        // 'bit.ly',
    ],

    /*
     * List of reserved or not allowed URL ending
     *
     * Some of them represent things that look like folder names in public
     * folders. Feel free to add keywords that you want to prevent, for
     * example rude words.
     */
    'reserved_keyword' => [
        // Reserved for future use
        'build', 'hot', // When Vite is running in development
        'vendor',       // Packages (ex: laravel/telescope)
        'assets', 'fonts', 'images', 'img', 'storage',
    ],

    'web_title' => true,

    /*
    |--------------------------------------------------------------------------
    | Visiting
    |--------------------------------------------------------------------------
    */

    /*
     * HTTP redirect status code.
     */
    'redirect_status_code' => 302,

    /*
     * Indicates that the response remains fresh until N seconds after the response
     * is generated.
     */
    'redirect_cache_max_age' => 30,

    /*
     * Determine whether bot visits count or not.
     *
     * - true: bot will be counted as a visitor
     * - false: bots will not be counted as visitors
     */
    'track_bot_visits' => false,
];
