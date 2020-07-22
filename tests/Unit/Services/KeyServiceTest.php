<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Services\KeyService;
use Tests\TestCase;
use Mockery;

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
     */
    public function keyRemainingInPercent()
    {
        $krip = Mockery::mock(KeyService::class)->makePartial();
        $krip
            ->shouldReceive([
                'keyCapacity' => 10,
                'numberOfUsedKey' => 11,
            ]);
        $response = $krip->keyRemainingInPercent();
        $this->assertSame('0%', $response);

        $krip = Mockery::mock(KeyService::class)->makePartial();
        $krip
            ->shouldReceive([
                'keyCapacity' => 1000,
                'numberOfUsedKey' => 999,
            ]);
        $response = $krip->keyRemainingInPercent();
        $this->assertSame('0.01%', $response);

        $krip = Mockery::mock(KeyService::class)->makePartial();
        $krip
            ->shouldReceive([
                'keyCapacity' => 1000,
                'numberOfUsedKey' => 5,
            ]);
        $response = $krip->keyRemainingInPercent();
        $this->assertSame('99.99%', $response);
    }
}
