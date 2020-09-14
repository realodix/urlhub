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
    public function has_many_url()
    {
        $user = User::factory()->create();

        Url::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->url()->exists());
    }
}
