<?php

return [
    'app_version' => '1.x-dev',

    /*
     * List of blocked domain
     *
     * This list is used to prevent shortening of urls that contain one of the
     * domains below.
     */
    'blacklist_domain' => [
        // 'bit.ly',
    ],

    /*
     * List of blocked keywords
     *
     * Specify keywords that are not allowed to be used as short URL keys,
     * both for custom links and randomly generated links. Enter a keyword in
     * one format only (e.g.: laravel), and all its variations (e.g.: Laravel,
     * LaRaVeL) will be blocked.
     */
    'blacklist_keyword' => [
        // 'laravel',
    ],

    /*
     * List of blocked usernames
     *
     * Usernames that are blocked and cannot be used during new user registration.
     */
    'blacklist_username' => [
        // 'advertise',
    ],

    /*
     * The HTTP status code used for redirect responses.
     */
    'redirect_status_code' => 302,

    /*
     * Specify the Cache-Control max-age (in seconds) for redirect responses.
     * Set to 0 to prevent caching.
     */
    'redirect_cache_lifetime' => 30,
];
