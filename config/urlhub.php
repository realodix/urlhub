<?php

return [
    'app_version' => '1.18.x-dev',

    /*
     * List of non allowed domain
     *
     * This list is used to prevent shortening of urls that contain one of the
     * domains below.
     */
    'blacklist_domain' => [
        // 'bit.ly',
    ],

    /*
     * A list of keywords that are not allowed to be used as short URL keys,
     * either for custom links or randomly generated ones.
     */
    'blacklist_keyword' => [
        // ...
    ],

    /*
     * The HTTP status code to use when redirecting a visitor to the original URL.
     */
    'redirection_status_code' => 302,

    'blacklist_username' => [
        // 'advertise',
    ],
];
