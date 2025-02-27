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

    #[PHPUnit\Test]
    public function getTimezone(): void
    {
        $user = User::factory()->create();
        $this->assertSame(config('app.timezone'), $user->timezone);

        $user = User::factory()->create(['timezone' => 'America/New_York']);
        $this->assertSame('America/New_York', $user->timezone);
    }

    /**
     * Jumlah tamu yang memiliki tanda tangan yang berbeda.
     */
    #[PHPUnit\Test]
    public function guestUserCount(): void
    {
        Url::factory()->count(2)->guest()->create();
        $this->assertSame(2, (new User)->guestUserCount());
    }

    /**
     * Semua tamu yang memiliki tanda tangan yang identik, harus disatukan.
     */
    #[PHPUnit\Test]
    public function guestUserCount2(): void
    {
        Url::factory()->count(5)->guest()->create(['user_uid' => 'foo']);
        $this->assertSame(1, (new User)->guestUserCount());
    }
}
