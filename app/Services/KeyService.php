<?php

namespace App\Services;

use App\Models\Url;
use RandomLib\Factory as RandomLibFactory;

class KeyService
{
    protected $url;

    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->url = new Url;
    }

    /**
     * @codeCoverageIgnore
     * Make sure the random string generated by randomStringGenerator() is
     * truly unique.
     */
    public function randomKey()
    {
        $randomKey = $this->randomStringGenerator();

        // If it is already used (not available), find the next available
        // string.
        $generatedRandomKey = $this->url->whereKeyword($randomKey)->first();
        while ($generatedRandomKey) {
            $randomKey = $this->randomStringGenerator();
            $generatedRandomKey = $this->url->whereKeyword($randomKey)->first();
        }

        return $randomKey;
    }

    /**
     * @codeCoverageIgnore
     * Generate random strings using RandomLib.
     *
     * @return string
     */
    public function randomStringGenerator()
    {
        $alphabet = uHub('hash_char');
        $length = uHub('hash_length');

        $factory = new RandomLibFactory();
        $randomString = $factory->getMediumStrengthGenerator()->generateString($length, $alphabet);

        return $randomString;
    }

    /**
     * Counts the maximum number of random strings that can be generated by a
     * random string generator.
     *
     * @return int
     */
    public function keyCapacity()
    {
        $alphabet = strlen(uHub('hash_char'));
        $length = uHub('hash_length');

        // Untuk kebutuhan di saat pengujian, dimana saat pengujian dibutuhkan
        // nilai yang dikembalikan adalah 0. Dalam produksi, kondisi ini tidak
        // diperlukan karena sudah dilakukan validasi untuk tidak mengembalikan
        // angka 0, maka kedepannya Kami mencoba untuk memanipulasi data yang
        // dikembalikan.
        if ($length == 0) {
            return 0;
        }

        return pow($alphabet, $length);
    }

    /**
     * Count the remaining random strings that can still be generated by a
     * random string generator.
     *
     * @return int
     */
    public function keyRemaining()
    {
        $keyCapacity = $this->keyCapacity();
        $numberOfUsedKey = $this->numberOfUsedKey();

        return max(($keyCapacity - $numberOfUsedKey), 0);
    }

    /**
     * Number of unique keys used as short url keys. Calculations performed by
     * the sum total of random string generated by the random string generator
     * plus total custom key that has characteristics similar to the random
     * string generated by the random string generator.
     */
    public function numberOfUsedKey()
    {
        $hashLength = uHub('hash_length');
        $regexPattern = '[a-zA-Z0-9]{'.$hashLength.'}';

        $randomKey = $this->url->whereIsCustom(false)->count();
        $customKey = $this->url->whereIsCustom(true)
            ->whereRaw('LENGTH(keyword) = ?', [$hashLength])
            ->whereRaw("keyword REGEXP '.$regexPattern.'")
            ->count();
        $numberOfUsedKey = $randomKey + $customKey;

        return $numberOfUsedKey;
    }
}
