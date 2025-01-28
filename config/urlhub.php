<?php

return [
    'app_version' => '1.9.0-dev',

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
];
