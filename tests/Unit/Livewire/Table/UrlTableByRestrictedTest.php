<?php

namespace Tests\Unit\Livewire\Table;

use App\Livewire\Table\UrlTableByRestricted;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

class UrlTableByRestrictedTest extends TestCase
{
    #[PHPUnit\Test]
    public function allUsesr(): void
    {
        $urlForUser1 = Url::factory()->create([
            'expires_at' => now()->subDay(),
        ]);
        $urlForUser2 = Url::factory()->create([
            'expires_at' => now()->subDay(),
        ]);

        $component = Livewire::test(UrlTableByRestricted::class, ['author' => null]);
        $urls = $component->instance()->datasource()->get();

        $this->assertCount(2, $urls);
        $this->assertTrue($urls->contains($urlForUser1));
        $this->assertTrue($urls->contains($urlForUser2));
    }

    #[PHPUnit\Test]
    public function specificUser(): void
    {
        $urlForUser1 = Url::factory()->create([
            'expires_at' => now()->subDay(),
        ]);
        $urlForUser2 = Url::factory()->create([
            'expires_at' => now()->subDay(),
        ]);

        $component = Livewire::test(UrlTableByRestricted::class, ['author' => $urlForUser1->author]);
        $urls = $component->instance()->datasource()->get();

        $this->assertCount(1, $urls);
        $this->assertTrue($urls->contains($urlForUser1));
        $this->assertFalse($urls->contains($urlForUser2));
    }

    #[PHPUnit\Test]
    public function expired(): void
    {
        $user = User::factory()->create();

        // === Non-Restricted URLs (should not be included) ===
        // 1. Belongs to another user
        Url::factory()->create(['expires_at' => now()->subDay()]);
        // 2. No limits set, not password protected
        $urlNotRestricted = Url::factory()->create([
            'user_id' => $user->id,
            'password' => null,
            'expires_at' => null,
            'expired_clicks' => null,
        ]);
        // 3. expires_at is in the future
        Url::factory()->create([
            'user_id' => $user->id,
            'password' => null,
            'expires_at' => now()->addDay(),
        ]);
        // 4. clicks are less than expired_clicks
        $urlNotExpiredByClicks = Url::factory()->create([
            'user_id' => $user->id,
            'password' => null,
            'expired_clicks' => 2,
        ]);
        Visit::factory()->for($urlNotExpiredByClicks)->create();
        // 5. expired_clicks is 0 (should be ignored by the scope)
        $urlExpiredClicksIsZero = Url::factory()->create([
            'user_id' => $user->id,
            'password' => null,
            'expired_clicks' => 0,
        ]);
        Visit::factory()->for($urlExpiredClicksIsZero)->create();

        // === Restricted URLs (should be included) ===
        // 6. Password protected
        $urlWithPassword = Url::factory()->create([
            'user_id' => $user->id,
            'password' => 'secret',
            'expires_at' => null,
        ]);
        // 7. Expired by date
        $expiredByDate = Url::factory()->create([
            'user_id' => $user->id,
            'password' => null,
            'expires_at' => now()->subDay(),
        ]);
        // 8. Expired by clicks (equal to limit)
        $expiredByClicks = Url::factory()->create([
            'user_id' => $user->id,
            'password' => null,
            'expired_clicks' => 1,
        ]);
        Visit::factory()->for($expiredByClicks)->create();

        // === Assertions ===
        $component = Livewire::test(UrlTableByRestricted::class, ['author' => $user]);

        $restrictedUrls = $component->instance()->datasource()->get();

        $this->assertCount(3, $restrictedUrls);
        $this->assertTrue($restrictedUrls->contains($urlWithPassword));
        $this->assertTrue($restrictedUrls->contains($expiredByDate));
        $this->assertTrue($restrictedUrls->contains($expiredByClicks));

        $this->assertFalse($restrictedUrls->contains($urlNotRestricted));
        $this->assertFalse($restrictedUrls->contains($urlNotExpiredByClicks));
        $this->assertFalse($restrictedUrls->contains($urlExpiredClicksIsZero));
    }
}
