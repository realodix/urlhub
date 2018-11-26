<?php

return [
    'version' => 'build.181126',

    // Number of symbols in generating unique url_key. If hash_size_1 == hash_size_2,
    // hash_size_2 is automatically declared to be of no value.
    'hash_size_1'   => env('HASH_SIZE_1', 6), // >= 1
    'hash_size_2'   => env('HASH_SIZE_2', 7), // >= 0

    // Symbols to be used in generating unique url_key.
    'hash_alphabet' => env(
                        'HASH_ALPHABET',
                        '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
                       ),

    'domains_blocked' => [
        config('app.url'),
        // 'bit.ly',
        // 'adf.ly',
        // 'goo.gl',
        // 't.co',
    ],
];
