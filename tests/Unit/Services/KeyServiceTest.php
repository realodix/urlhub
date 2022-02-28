<?php

namespace Tests\Unit\Services;

use App\Services\KeyService;
use Tests\TestCase;

class KeyServiceTest extends TestCase
{
    /**
     * @var \App\Services\KeyService
     */
    protected $keySrvc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->keySrvc = new KeyService();
    }

    /**
     * @test
     * @group u-service
     */
    public function urlKey()
    {
        config(['urlhub.hash_length' => 6]);

        $actual = 'https://github.com/realodix/urlhub';
        $expected = 'urlhub';
        $this->assertSame($expected, $this->keySrvc->urlKey($actual));
    }
}
