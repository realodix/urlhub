<?php

namespace Tests\Unit\Models;

use App\Models\{Url, User};
use PHPUnit\Framework\Attributes\{Group, Test};
use Tests\TestCase;

class UserTest extends TestCase
{
    #[Test]
    #[Group('u-model')]
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
    #[Test]
    #[Group('u-model')]
    public function totalGuestUsers(): void
    {
        Url::factory()->count(2)->create(['user_id' => Url::GUEST_ID]);
        $this->assertSame(2, (new User)->totalGuestUsers());
    }

    /**
     * Semua tamu yang memiliki tanda tangan yang identik, harus disatukan.
     */
    #[Test]
    #[Group('u-model')]
    public function totalGuestUsers2(): void
    {
        Url::factory()->count(5)->create(['user_id' => Url::GUEST_ID, 'user_sign' => 'foo']);
        $this->assertSame(1, (new User)->totalGuestUsers());
    }
}
