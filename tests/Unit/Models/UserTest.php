<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
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
     */
    public function has_many_url()
    {
        $user = factory(User::class)->create();

        factory(Url::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->url()->exists());
    }
}
