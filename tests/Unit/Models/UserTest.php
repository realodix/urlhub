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
    public function hasManyUrl()
    {
        $user = User::factory()->create();

        Url::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->urls()->exists());
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
