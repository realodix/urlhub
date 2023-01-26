<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     * @group u-model
     */
    public function hasManyUrlModel()
    {
        $user = User::factory()
            ->has(Url::factory())
            ->create();

        $this->assertEquals(1, $user->urls->count());
        $this->assertInstanceOf(Url::class, $user->urls->first());
    }

    /**
     * The number of guests is calculated based on a unique IP.
     *
     * @test
     * @group u-model
     */
    public function totalGuestUsers()
    {
        $this->assertSame(0, (new User)->totalGuestUsers());
    }
}
