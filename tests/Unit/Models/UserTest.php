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
            'user_id' => $this->nonAdmin()->id,
        ]);

        $this->assertTrue($this->nonAdmin()->url()->exists());
    }

    /**
     * There are 2 users created, see setUp() method
     * on Tests\Support\Authentication class.
     *
     * @test
     */
    public function totalUser()
    {
        $user = new User;

        $this->assertSame(2, $user->totalUser());
    }

    /**
     * The number of guests is calculated based on IP,
     * see setUp() method on this class.
     *
     * @test
     */
    public function totalGuest()
    {
        $user = new User;

        $this->assertSame(2, $user->totalGuest());
    }
}
