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
            'user_id'  => null,
            'long_url' => 'https://laravel.com',
            'clicks'   => 10,
            'ip'       => '0.0.0.0',
        ]);

        factory(Url::class)->create([
            'user_id'  => null,
            'long_url' => 'https://laravel.com',
            'clicks'   => 10,
            'ip'       => '1.1.1.1',
        ]);
    }

    /** @test */
    public function has_many_url()
    {
        $url = factory(Url::class)->create([
            'user_id' => $this->user()->id,
        ]);

        $this->assertTrue($this->user()->url()->exists());
    }

    /** @test */
    public function totalUser()
    {
        $user = new User;

        // There are 2 users created
        // See Tests\Support\Authentication::setUp()
        $this->assertSame(2, $user->totalUser());
    }

    /**
     * The number of guests is calculated based on IP
     * See setUp().
     *
     * @test
     */
    public function totalGuest()
    {
        $user = new User;

        $this->assertSame(2, $user->totalGuest());
    }
}
