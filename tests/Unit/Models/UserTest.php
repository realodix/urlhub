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
            'clicks'   => 10,
            'ip'       => '0.0.0.0',
        ]);

        factory(Url::class)->create([
            'user_id'  => null,
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
     * There are 2 authenticated users that have been created,
     * see setUp() method on Tests\Support\Authentication class.
     *
     * @test
     */
    public function totalUser()
    {
        $user = new User;

        $this->assertSame(2, $user->totalUser());
    }

    /**
     * The number of guests is calculated based on a unique IP,
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
