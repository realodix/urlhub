<?php

namespace Tests\Unit\Models;

use App\Models\{Url, User};
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     * @group u-model
     */
    public function hasManyUrl()
    {
        $user = User::factory()->create();

        Url::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->url()->exists());
    }

    /**
     * The number of guests is calculated based on a unique IP.
     *
     * @test
     * @group u-model
     */
    public function guestCount()
    {
        $this->assertSame(0, (new User)->guestCount());
    }
}
