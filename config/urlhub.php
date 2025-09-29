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
     * The HTTP status code for redirects
     *
     * Specifies the HTTP status code used when redirecting a short URL to
     * its original destination.
     */
    'redirection_status_code' => 302,

    /*
     * List of blocked usernames
     *
     * Usernames that are blocked and cannot be used during new user registration.
     */
    'blacklist_username' => [
        // 'advertise',
    ],
];
