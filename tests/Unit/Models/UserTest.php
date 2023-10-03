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
    public function hasManyUrlModel(): void
    {
        $user = User::factory()
            ->has(Url::factory())
            ->create();

        $this->assertEquals(1, $user->urls->count());
        $this->assertInstanceOf(Url::class, $user->urls->first());
    }

    /**
     * The number of guests is calculated based on the user sign.
     *
     * @test
     * @group u-model
     */
    public function totalGuestUsers(): void
    {
        Url::factory(2)->create(['user_id' => Url::GUEST_ID]);
        $this->assertSame(2, (new User)->totalGuestUsers());

        // 2+1
        Url::factory(5)->create(['user_id' => Url::GUEST_ID, 'user_sign' => 'foo']);
        $this->assertSame(3, (new User)->totalGuestUsers());
    }
}
