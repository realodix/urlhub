<?php

namespace Tests\Unit\Services;

use App\Services\KeyService;
use Mockery;
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
     */
    public function keyCapacity()
    {
        $hashLength = uHub('hash_length');
        $hashCharLength = strlen(uHub('hash_char'));
        $keyCapacity = pow($hashCharLength, $hashLength);

        $this->assertSame($keyCapacity, $this->keySrvc->keyCapacity());
    }

    /**
     * @test
     * @group u-service
     * @dataProvider keyRemainingProvider
     */
    public function keyRemaining($kc, $nouk, $expected)
    {
        $mock = Mockery::mock(KeyService::class)->makePartial();
        $mock->shouldReceive([
            'keyCapacity'     => $kc,
            'numberOfUsedKey' => $nouk,
        ]);
        $actual = $mock->keyRemaining();

        $this->assertSame($expected, $actual);
    }

    public function keyRemainingProvider()
    {
        // keyCapacity(), numberOfUsedKey(), expected_result
        return [
            [1, 2, 0],
            [3, 2, 1],
        ];
    }

    /**
     * @test
     * @group u-service
     * @dataProvider keyRemainingInPercentProvider
     */
    public function keyRemainingInPercent($kc, $nouk, $expected)
    {
        $mock = Mockery::mock(KeyService::class)->makePartial();
        $mock->shouldReceive([
            'keyCapacity'     => $kc,
            'numberOfUsedKey' => $nouk,
        ]);
        $actual = $mock->keyRemainingInPercent();

        $this->assertSame($expected, $actual);
    }

    public function keyRemainingInPercentProvider()
    {
        // keyCapacity(), numberOfUsedKey(), expected_result
        return [
            [10, 10, '0%'],
            [10, 11, '0%'],
            [pow(10, 6), 999991, '0.01%'],
            [pow(10, 6), 50, '99.99%'],
        ];
    }
}
