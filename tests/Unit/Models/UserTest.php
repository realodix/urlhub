<?php

namespace Tests\Unit\Models;

use App\Url;
use App\User;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\User
 */
class UserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        factory(Url::class)->create([
            'user_id' => null,
            'clicks'  => 10,
            'ip'      => '0.0.0.0',
        ]);

        factory(Url::class)->create([
            'user_id' => null,
            'clicks'  => 10,
            'ip'      => '1.1.1.1',
        ]);
    }

    /**
     * @test
     * @group u-model
     * @covers ::url
     */
    public function has_many_url()
    {
        $user = factory(User::class)->create();

        factory(Url::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->url()->exists());
    }

    /**
     * There are 2 authenticated users that have been created,
     * see setUp() method on Tests\Support\Authentication class.
     *
     * @test
     * @group u-model
     * @covers ::totalUser
     */
    public function totalUser()
    {
        $user = new User;

        $this->assertSame(1, $user->totalUser());
    }

    /**
     * The number of guests is calculated based on a unique IP,
     * see setUp() method on this class.
     *
     * @test
     * @group u-model
     * @covers ::totalGuest
     */
    public function totalGuest()
    {
        $user = new User;

        $this->assertSame(2, $user->totalGuest());
    }
}
