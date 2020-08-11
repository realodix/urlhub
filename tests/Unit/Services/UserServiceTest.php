<?php

namespace Tests\Unit\Services;

use App\Services\UserService;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    /**
     * @var \App\Services\UserService
     */
    protected $userSrvc;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userSrvc = new UserService();
    }

    /**
     * There are 2 authenticated users that have been created, see setUp()
     * method on Tests\Support\Authentication class.
     *
     * @test
     * @group u-service
     */
    public function userCount()
    {
        $this->assertSame(1, $this->userSrvc->userCount());
    }

    /**
     * The number of guests is calculated based on a unique IP.
     *
     * @test
     * @group u-service
     */
    public function guestCount()
    {
        $this->assertSame(0, $this->userSrvc->guestCount());
    }
}
