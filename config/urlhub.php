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
    'public_site' => env('UH_PUBLIC_SITE', true),

    /*
     * Enable users registration. If disabled it, no one can register.
     */
    'registration' => env('UH_REGISTRATION', true),

    /*
     * Enable/Disable to allow unregistered users see shortened links
     * statistics.
     */
    'guest_show_stat' => env('UH_GUEST_SHOW_STAT', true),

    /*
    |--------------------------------------------------------------------------
    | Shorten URL
    |--------------------------------------------------------------------------
    */

    /*
     * The expected (and maximum) number of characters in generating unique
     * keyword.
     */
    'hash_length' => env('HASH_LENGTH', 6), // >= 1

    /*
     * Characters to be used in generating unique keyword. For convenience,
     * currently the allowed characters are only alphanumeric consisting of
     * a limited set of characters belonging to the US-ASCII characters,
     * including digits (0-9), letters (A-Z, a-z).
     */
    'hash_char' => env(
        'HASH_CHAR',
        'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    ),

    /*
     * List of non allowed domain.
     *
     * This list is used to prevent shortening of urls that contain one of the
     * domains below.
     */
    'domain_blacklist' => [
        config('app.url'),
        // 'bit.ly',
    ],

    /*
     * List of reserved or not allowed URL ending.
     *
     * Some of them represent things that look like folder names in public
     * folders. Feel free to add keywords that you want to prevent, for
     * example rude words.
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
    | Visiting
    |--------------------------------------------------------------------------
    */

    /*
     * Tells if IP addresses from visitors should be obfuscated before storing
     * them in the database.
     *
     * Be careful!
     * Setting this to false will make your UrlHub instance no longer be in
     * compliance with the GDPR and other similar data protection regulations.
     */
    'anonymize_ip_addr' => env('UH_ANONYMIZE_IP_ADDR', true),

    /*
     * Configure the kind of redirect you want to use for your short URLs. You
     * can either set:
     * - 301 (Default behavior, visitors always hit the server).
     * - 302 (Better for SEO, visitors hit the server the first time and then
     *   cache the redirect).
     *
     * When selecting 301 redirects, you can also configure the time redirects
     * are cached, to mitigate deviations in stats.
     */
    'redirect_status_code' => env('UH_REDIRECT_STATUS_CODE', 301),

    /*
     * Set the amount of seconds that redirects should be cached when redirect
     * status is 301. Default values is 90.
     */
    'redirect_cache_lifetime' => env('UH_REDIRECT_CACHE_LIFETIME', 90),

    /*
    |--------------------------------------------------------------------------
    | General Feature
    |--------------------------------------------------------------------------
    */

    /*
     * Turn on/off the embed external content element.
     */
    'embed' => env('UH_EMBED', true),
];
