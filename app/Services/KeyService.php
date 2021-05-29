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
     * Step 1: Get the key directly from the given url
     * Step 2: If the key in step 1 is already in the database, then generate a random
     *         string.
     *
     * @param string $string
     * @return string
     */
    public function urlKey(string $string)
    {
        $length = config('urlhub.hash_length') * -1;

        $urlKey = substr(preg_replace('/[^a-z0-9]/i', '', $string), $length);

        $generatedRandomKey = $this->url->whereKeyword($urlKey)->first();
        while ($generatedRandomKey) {
            $urlKey = $this->randomString();
            $generatedRandomKey = $this->url->whereKeyword($urlKey)->first();
        }

        return $urlKey;
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

    /**
     * Calculates the maximum number of unique random strings that can be supplied.
     *
     * @return int
     */
    public function keyCapacity()
    {
        $alphabet = strlen(uHub('hash_char'));
        $length = uHub('hash_length');

        // for testing purposes only
        // tests\Unit\Middleware\UrlHubLinkCheckerTest.php
        if ($length == 0) {
            return 0;
        }

        return pow($alphabet, $length);
    }

    /**
     * Counts unique random strings that can be supplied.
     *
     * @return int
     */
    public function keyRemaining()
    {
        $keyCapacity = $this->keyCapacity();
        $numberOfUsedKey = $this->numberOfUsedKey();

        return max($keyCapacity - $numberOfUsedKey, 0);
    }

    public function keyRemainingInPercent()
    {
        $capacity = $this->keyCapacity();
        $used = $this->numberOfUsedKey();
        $remaining = $this->keyRemaining();

        $result = round(($remaining / $capacity) * 100, 2);

        if (($result == 0) && ($capacity <= $used)) {
            return '0%';
        } elseif (($result == 0) && ($capacity > $used)) {
            return '0.01%';
        } elseif (($result == 100) && ($capacity != $remaining)) {
            return '99.99%';
        }

        return $result.'%';
    }

    /**
     * The number of unique random strings used as the short url key.
     *
     * Calculation formula:
     * numberOfUsedKey = randomKey + customKey
     *
     * customKey must be similar to randomKey, such as having the same character length.
     */
    public function numberOfUsedKey()
    {
        $hashLength = uHub('hash_length');
        $regexPattern = '['.uHub('hash_char').']{'.$hashLength.'}';

        $randomKey = $this->url->whereIsCustom(false)
            ->whereRaw('LENGTH(keyword) = ?', [$hashLength])
            ->count();

        $customKey = $this->url->whereIsCustom(true)
            ->whereRaw('LENGTH(keyword) = ?', [$hashLength])
            ->whereRaw("keyword REGEXP '".$regexPattern."'")
            ->count();

        return $randomKey + $customKey;
    }
}
