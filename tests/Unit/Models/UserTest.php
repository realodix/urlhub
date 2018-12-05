<?php

namespace Tests\Unit\Models;

use App\User;
use App\Url;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_it_has_many_url()
    {
        $user = factory(User::class)->create();
        $url = factory(Url::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->url()->exists());
    }
}
