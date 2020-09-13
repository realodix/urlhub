<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Services\KeyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class KeyServiceTest extends TestCase
{
    use RefreshDatabase;

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

    /**
     * @test
     * @group u-service
     */
    public function numberOfUsedKey()
    {
        config(['urlhub.hash_char' => 'abc']);

        Url::factory()->create([
            'keyword' => $this->keySrvc->randomKey(),
        ]);
        $this->assertSame(1, $this->keySrvc->numberOfUsedKey());

        Url::factory()->create([
            'keyword'   => str_repeat('a', uHub('hash_length')),
            'is_custom' => 1,
        ]);
        $this->assertSame(2, $this->keySrvc->numberOfUsedKey());

        Url::factory()->create([
            'keyword'   => str_repeat('b', uHub('hash_length') + 1),
            'is_custom' => 1,
        ]);
        $this->assertSame(2, $this->keySrvc->numberOfUsedKey());

        config(['urlhub.hash_length' => uHub('hash_length') + 2]);
        $this->assertSame(0, $this->keySrvc->numberOfUsedKey());
    }

    /**
     * @test
     * @group u-service
     */
    public function numberOfUsedKey2()
    {
        config(['urlhub.hash_length' => 3]);

        config(['urlhub.hash_char' => 'foo']);
        Url::factory()->create([
            'keyword'   => 'foo',
            'is_custom' => 1,
        ]);
        $this->assertSame(1, $this->keySrvc->numberOfUsedKey());

        config(['urlhub.hash_char' => 'bar']);
        Url::factory()->create([
            'keyword'   => 'bar',
            'is_custom' => 1,
        ]);
        $this->assertSame(1, $this->keySrvc->numberOfUsedKey());

        config(['urlhub.hash_char' => 'foobar']);
        $this->assertSame(2, $this->keySrvc->numberOfUsedKey());

        config(['urlhub.hash_char' => 'fooBar']);
        $this->assertSame(1, $this->keySrvc->numberOfUsedKey());

        config(['urlhub.hash_char' => 'FooBar']);
        $this->assertSame(0, $this->keySrvc->numberOfUsedKey());
    }
}
