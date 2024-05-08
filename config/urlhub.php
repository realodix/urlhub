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
    |--------------------------------------------------------------------------
    | Shorten URL
    |--------------------------------------------------------------------------
    */

    /*
     * The expected length of the keyword generated when creating a new short URL.
     */
    'keyword_length' => env('UH_KEYWORD_LENGTH', 5), // >= 1

    /**
     * Minimum length of custom keyword.
     */
    'custom_keyword_min_length' => env('UH_CUSTOM_KEYWORD_MIN_LENGTH', 3),

    /**
     * Maximum length of custom keyword.
     */
    'custom_keyword_max_length' => env('UH_CUSTOM_KEYWORD_MAX_LENGTH', 11),

    /*
     * List of non allowed domain.
     *
     * This list is used to prevent shortening of urls that contain one of the
     * domains below.
     */
    'domain_blacklist' => [
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
        // Reserved for future use
        'build', // When Vite is running in development
        'hot', // When Vite is running in development
        'vendor', // When installing packages (ex: laravel/telescope)

        // Others
        'images',
        'fonts',
        'storage',
    ],

    'web_title' => env('UH_WEB_TITLE', true),

    /*
    |--------------------------------------------------------------------------
    | Visiting
    |--------------------------------------------------------------------------
    */

    /*
     * Configure the kind of redirect you want to use for your short URLs. You
     * can either set:
     * - 301
     * - 302
     *
     * When selecting 301 redirects, you can also configure the time redirects
     * are cached, to mitigate deviations in stats.
     */
    'redirect_status_code' => env('UH_REDIRECT_STATUS_CODE', 302),

    /*
     * Set the amount of seconds that redirects should be cached when redirect
     * status is 301. Default values is 30.
     */
    'redirect_cache_max_age' => env('UH_REDIRECT_CACHE_MAX_AGE', 30),

    /**
     * Determine whether bot visits are logged or not
     *
     * - TRUE: Logs bot visits in the visitor log
     * - FALSE: Doesn't log bot visits in visitor logs
     */
    'track_bot_visits' => env('UH_TRACK_BOT_VISITS', false),

    /*
    |--------------------------------------------------------------------------
    | QR codes
    |--------------------------------------------------------------------------
    */

    /**
     * Type: bool
     * Accepted values: true or false
     */
    'qrcode' => env('QRCODE', true),

    /**
     * Determines the width/height in pixels.
     *
     * Type: int
     * Accepted values: 50 to 1000
     */
    'qrcode_size' => env('QRCODE_SIZE', 170),

    /**
     * The space in pixels between the QR code itself and the border of the image.
     *
     * Type: int (positive)
     */
    'qrcode_margin' => env('QRCODE_MARGIN', 0),

    /**
     * Type: string
     * Accepted values: png or svg
     */
    'qrcode_format' => env('QRCODE_FORMAT', 'png'),

    /**
     * Determine error correction levels to restore data if the code is dirty or
     * damaged.
     *
     * Type: string
     * Accepted values: l, m, q, h
     *
     * See https://www.qrcode.com/en/about/error_correction.html for more information.
     */
    'qrcode_error_correction' => env('QRCODE_ERROR_CORRECTION', 'm'),

    /**
     * Tells if the block size should be rounded, making the QR code more readable,
     * but potentially adding some extra margin as a side effect.
     *
     * Type: bool
     * Accepted values: true or false
     */
    'qrcode_round_block_size' => env('QRCODE_ROUND_BLOCK_SIZE', true),
];
