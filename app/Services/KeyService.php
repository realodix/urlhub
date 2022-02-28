<?php

namespace App\Services;

use App\Models\Url;
use RandomLib\Factory as RandomLibFactory;

class KeyService
{
    /**
     * @var \App\Models\Url
     */
    protected $url;

    /**
     * KeyService constructor.
     */
    public function __construct()
    {
        $this->url = new Url;
    }

    /**
     * @param  string  $string
     * @return string
     */
    public function urlKey(string $string)
    {
        $length = config('urlhub.hash_length') * -1;

        // Step 1
        // Generate unique key from truncated long URL.
        $uniqueUrlKey = substr(preg_replace('/[^a-z0-9]/i', '', $string), $length);

        // Step 2
        // If the unique key is not available (already in the database) , then generate a
        // random string.
        $generatedRandomKey = $this->url->whereKeyword($uniqueUrlKey)->first();
        while ($generatedRandomKey) {
            $uniqueUrlKey = $this->randomString();
            $generatedRandomKey = $this->url->whereKeyword($uniqueUrlKey)->first();
        }

        return $uniqueUrlKey;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function randomString()
    {
        $alphabet = uHub('hash_char');
        $length = uHub('hash_length');
        $factory = new RandomLibFactory();

        return $factory->getMediumStrengthGenerator()->generateString($length, $alphabet);
    }
}
