<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

class HelpersTest extends TestCase
{
    /**
     * @test
     */
    public function getCountriesWithUnknownIp()
    {
        $countries = getCountries('127.0.0.1');

        $this->assertEquals('N/A', $countries['countryCode']);
        $this->assertEquals('Unknown', $countries['countryName']);
    }
}
