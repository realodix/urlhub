<?php

namespace Tests\Unit\Models;

use App\Url;
use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();

        factory(Url::class)->create([
            'user_id'  => 0,
            'long_url' => 'https://laravel.com',
            'clicks'   => 10,
            'ip'       => '0.0.0.0',
        ]);

        factory(Url::class)->create([
            'user_id'  => 0,
            'long_url' => 'https://laravel.com',
            'clicks'   => 10,
            'ip'       => '1.1.1.1',
        ]);
    }

    /** @test */
    public function has_many_url()
    {
        $user = factory(User::class)->create();
        $url = factory(Url::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->url()->exists());
    }

    /** @test */
    public function totalUser()
    {
        $user = new User;

        $this->assertEquals(2, $user->totalUser());
    }

    /** @test */
    public function totalGuest()
    {
        $user = new User;

        $this->assertEquals(2, $user->totalGuest());
    }
}
