<?php

namespace Tests\Unit\Services;

use App\Models\Url;
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
     */
    public function keyRemaining()
    {
        factory(Url::class, 2)->create();

        config()->set('urlhub.hash_char', '1');
        config()->set('urlhub.hash_length', 1);

        // 1 - 2 = must be 0
        $this->assertSame(0, $this->keySrvc->keyRemaining());

        config()->set('urlhub.hash_char', '123');

        // (3^1) - 2 = 1
        $this->assertSame(1, $this->keySrvc->keyRemaining());
    }

    /**
     * @test
     * @group u-service
     * @dataProvider keyRemainingInPercentProvider
     */
    public function keyRemainingInPercent($kc, $nouk, $result)
    {
        $krip = Mockery::mock(KeyService::class)->makePartial();
        $krip->shouldReceive([
            'keyCapacity'     => $kc,
            'numberOfUsedKey' => $nouk,
        ]);
        $response = $krip->keyRemainingInPercent();

        $this->assertSame($result, $response);
    }

    public function keyRemainingInPercentProvider()
    {
        return [
            [10, 10, '0%'],
            [10, 11, '0%'],
            [pow(10, 3), 999, '0.01%'],
            [pow(10, 3), 5, '99.99%'],
        ];
    }
}
