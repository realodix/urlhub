<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\User;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('model')]
class UserTest extends TestCase
{
    #[PHPUnit\Test]
    public function hasManyUrlModel(): void
    {
        $user = User::factory()
            ->has(Url::factory())
            ->create();

        $this->assertEquals(1, $user->urls->count());
        $this->assertInstanceOf(Url::class, $user->urls->first());
    }

    /**
     * Jumlah tamu yang memiliki tanda tangan yang berbeda.
     */
    #[PHPUnit\Test]
    public function totalGuestUsers(): void
    {
        Url::factory()->count(2)->create(['user_id' => Url::GUEST_ID]);
        $this->assertSame(2, (new User)->totalGuestUsers());
    }

    /**
     * Semua tamu yang memiliki tanda tangan yang identik, harus disatukan.
     */
    #[PHPUnit\Test]
    public function totalGuestUsers2(): void
    {
        Url::factory()->count(5)->create(['user_id' => Url::GUEST_ID, 'user_sign' => 'foo']);
        $this->assertSame(1, (new User)->totalGuestUsers());
    }

    /**
     * Test the signature of the user.
     */
    public function testSignature(): void
    {
        $user = app(User::class);
        $this->assertTrue(strlen($user->signature()) >= 16);

        $user = $this->basicUser();
        $this->actingAs($user)
            ->post(route('su_create'), ['long_url' => 'https://laravel.com']);
        $this->assertEquals($user->id, $user->signature());
    }
}
