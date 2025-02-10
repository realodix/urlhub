<?php

namespace Tests\Unit\Services;

use App\Services\UserService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class UserServiceTest extends TestCase
{
    public function testSignature(): void
    {
        $userService = app(UserService::class);
        $this->assertTrue(strlen($userService->signature()) >= 16);

        $user = $this->basicUser();
        $this->actingAs($user)
            ->post(route('link.create'), ['long_url' => 'https://laravel.com']);
        $this->assertEquals($user->id, $userService->signature());
    }
}
