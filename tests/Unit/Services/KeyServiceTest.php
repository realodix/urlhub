<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Services\KeyService;
use Tests\TestCase;

class KeyServiceTest extends TestCase
{
    protected $keySrvc;

    protected function setUp(): void
    {
        parent::setUp();

        $this->keySrvc = new KeyService();
    }

    /**
     * @test
     * @group u-service
     * @dataProvider keyCapacityProvider
     */
    public function keyCapacity($hashLength, $expected)
    {
        config()->set('urlhub.hash_length', $hashLength);

        $this->assertSame($expected, $this->keySrvc->keyCapacity());
    }

    public function keyCapacityProvider()
    {
        return [
            [1, 62], // (62^1)
            [2, 3844], // $hash_char_length^$hash_length or 62^2
        ];
    }

    /**
     * @test
     * @group u-service
     */
    public function keyRemaining()
    {
        factory(Url::class, 5)->create();

        config()->set('urlhub.hash_char', '1234567');
        config()->set('urlhub.hash_length', 1);
 
        // 7 - 5 - (2+1) = must be 0
        $this->assertSame(0, $this->keySrvc->keyRemaining());

        config()->set('urlhub.hash_length', 2);

        // (7^2) - 5 - (2+1) = 41
        $this->assertSame(41, $this->keySrvc->keyRemaining());
    }
}
