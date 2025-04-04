<?php

namespace Tests\Unit\Models;

use App\Enums\UserType;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('model')]
class VisitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->visit = new Visit;
    }

    public function testFactory(): void
    {
        $m = Visit::factory()->guest()->create();

        $this->assertSame(\App\Enums\UserType::Guest, $m->user_type);
    }

    #[PHPUnit\Test]
    public function belongsToUrlModel(): void
    {
        $visit = Visit::factory()->create();

        $this->assertEquals(1, $visit->url->count());
        $this->assertInstanceOf(Url::class, $visit->url);
    }

    #[PHPUnit\Test]
    public function authUserLinkVisits(): void
    {
        $user = $this->basicUser();
        $nCurrentUser = 8;
        $nUser = 6;

        Visit::factory()->count($nCurrentUser)
            ->for(Url::factory()->state(['user_id' => $user->id]))
            ->create();
        Visit::factory()->count($nUser)
            ->for(Url::factory())
            ->create();

        $this->actingAs($user);
        $this->assertSame($nCurrentUser, $this->visit->authUserLinkVisits());
        $this->assertSame($nCurrentUser + $nUser, $this->visit->userLinkVisits());
    }

    #[PHPUnit\Test]
    public function userLinkVisits(): void
    {
        $nUser = 6;
        $nGuest = 4;

        Visit::factory()->count($nUser)
            ->for(Url::factory())
            ->create();

        Visit::factory()->count($nGuest)
            ->for(Url::factory()->guest())
            ->create();

        $this->assertSame($nUser, $this->visit->userLinkVisits());
        $this->assertSame($nUser + $nGuest, $this->visit->count());
    }

    #[PHPUnit\Test]
    public function guestLinkVisits(): void
    {
        $nUser = 6;
        $nGuest = 4;

        Visit::factory()->count($nUser)
            ->for(Url::factory())
            ->create();

        Visit::factory()->count($nGuest)
            ->for(Url::factory()->guest())
            ->create();

        $this->assertSame($nGuest, $this->visit->guestLinkVisits());
        $this->assertSame($nUser + $nGuest, $this->visit->count());
    }

    #[PHPUnit\Test]
    public function userVisits()
    {
        $this->visitCountData();

        $this->assertEquals(1, $this->visit->userVisits());
    }

    #[PHPUnit\Test]
    public function guestVisits()
    {
        $this->visitCountData();

        $this->assertEquals(5, $this->visit->guestVisits());
    }

    #[PHPUnit\Test]
    public function uniqueGuestVisits()
    {
        $this->visitCountData();

        $this->assertEquals(3, $this->visit->uniqueGuestVisits());
    }

    private function visitCountData()
    {
        Visit::factory()->create(); // user1
        Visit::factory()->guest()->create(); // guest1
        Visit::factory()->guest()->count(2)->create(['user_uid' => 'foo']); // guest2
        Visit::factory()->count(2)->create([ // bot
            'user_type' => UserType::Bot,
            'user_uid' => 'bar',
        ]);
    }

    public function test_get_top_referrers()
    {
        Visit::factory()->count(5)->create();
        $topReferrers = Visit::getTopReferrers();
        $this->assertEquals($topReferrers->first()->total, $topReferrers->max('total'));

        // Unique referrers
        $this->assertEquals(1, $topReferrers->count());
        Visit::factory()->count(5)->create(['referer' => 'foo']);
        Visit::factory()->count(5)->create(['referer' => 'bar']);
        $topReferrers = Visit::getTopReferrers(limit: 2);
        $this->assertEquals(2, $topReferrers->count());
    }

    public function test_get_top_referrers_for_auth_user()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();

        // Create some URLs belonging to the user
        Url::factory()->for($user, 'author')->hasVisits(5, ['referer' => 'foo'])->create();
        Url::factory()->for($user, 'author')->hasVisits(3, ['referer' => 'baz'])->create();
        Url::factory()->for($user, 'author')->hasVisits(6, ['referer' => 'foo'])->create(); // Same referrer
        Url::factory()->for($user, 'author')->hasVisits(4, ['referer' => 'bar'])->create();
        Url::factory()->hasVisits(7, ['referer' => 'foo'])->create(); // Same referrer from other user
        Url::factory()->hasVisits(7, ['referer' => 'https://example.com'])->create(); // From other users

        // Get the top referrers for the authenticated user
        $topReferrers = Visit::getTopReferrers($user, limit: 2);

        // Assertions
        $this->assertCount(2, $topReferrers);

        // Check if the referrers are ordered correctly by visit count
        $this->assertEquals('foo', $topReferrers[0]->referer);
        $this->assertEquals(11, $topReferrers[0]->total);
        $this->assertEquals('bar', $topReferrers[1]->referer);
        $this->assertEquals(4, $topReferrers[1]->total);

        // Just 2, so other referrers are not included
        $this->assertNotContains('baz', $topReferrers->pluck('referer')->toArray());
        // Check if referrers from other users are not included
        $this->assertNotContains('https://example.com', $topReferrers->pluck('referer')->toArray());
    }

    public function test_get_top_referrers_for_auth_user_with_no_visits()
    {
        $user = User::factory()->create();
        $topReferrers = Visit::getTopReferrers($user);
        $this->assertCount(0, $topReferrers);
    }

    public function test_get_top_browsers()
    {
        // Create some visits with different browsers
        Visit::factory()->count(5)->create(['browser' => 'foo']);
        Visit::factory()->count(4)->create(['browser' => 'baz']);
        Visit::factory()->count(6)->create(['browser' => 'bar']);
        Visit::factory()->count(7)->create(['browser' => 'foo']);

        // Get the top browsers
        $topBrowsers = Visit::getTopBrowsers(limit: 2);

        // Assertions
        $this->assertCount(2, $topBrowsers);

        // Check if the browsers are ordered correctly by visit count
        $this->assertEquals('foo', $topBrowsers[0]->browser);
        $this->assertEquals(12, $topBrowsers[0]->total);
        $this->assertEquals('bar', $topBrowsers[1]->browser);
        $this->assertEquals(6, $topBrowsers[1]->total);

        $this->assertNotContains('baz', $topBrowsers->pluck('browser')->toArray());
    }

    public function test_get_top_browsers_for_auth_user()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();

        // Create some URLs belonging to the user
        Url::factory()->for($user, 'author')->hasVisits(5, ['browser' => 'foo'])->create();
        Url::factory()->for($user, 'author')->hasVisits(1, ['browser' => 'baz'])->create();
        Url::factory()->for($user, 'author')->hasVisits(4, ['browser' => 'bar'])->create();
        Url::factory()->for($user, 'author')->hasVisits(6, ['browser' => 'foo'])->create();
        Url::factory()->hasVisits(7, ['browser' => 'Safari'])->create(); // From other users

        // Get the top browsers for the authenticated user
        $topBrowsers = Visit::getTopBrowsers($user, limit: 2);

        // Assertions
        $this->assertCount(2, $topBrowsers);

        // Check if the browsers are ordered correctly by visit count
        $this->assertEquals('foo', $topBrowsers[0]->browser);
        $this->assertEquals(11, $topBrowsers[0]->total);
        $this->assertEquals('bar', $topBrowsers[1]->browser);
        $this->assertEquals(4, $topBrowsers[1]->total);

        $this->assertNotContains('baz', $topBrowsers->pluck('browser')->toArray());
        $this->assertNotContains('Safari', $topBrowsers->pluck('browser')->toArray());
    }

    public function test_get_top_browsers_for_auth_user_with_no_visits()
    {
        $user = User::factory()->create();
        $topBrowsers = Visit::getTopBrowsers($user);
        $this->assertCount(0, $topBrowsers);
    }

    public function test_get_top_operating_systems()
    {
        // Create some visits with different operating systems
        Visit::factory()->count(5)->create(['os' => 'foo']);
        Visit::factory()->count(6)->create(['os' => 'bar']);
        Visit::factory()->count(4)->create(['os' => 'Android']);
        Visit::factory()->count(7)->create(['os' => 'foo']);

        // Get the top operating systems
        $topOS = Visit::getTopOperatingSystems(limit: 2);

        // Assertions
        $this->assertCount(2, $topOS);

        // Check if the operating systems are ordered correctly by visit count
        $this->assertEquals('foo', $topOS[0]->os);
        $this->assertEquals(12, $topOS[0]->total);
        $this->assertEquals('bar', $topOS[1]->os);

        $this->assertNotContains('Android', $topOS->pluck('os')->toArray());
    }

    public function test_get_top_operating_systems_for_auth_user()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();

        // Create some URLs belonging to the user
        Url::factory()->for($user, 'author')->hasVisits(5, ['os' => 'foo'])->create();
        Url::factory()->for($user, 'author')->hasVisits(6, ['os' => 'bar'])->create();
        Url::factory()->for($user, 'author')->hasVisits(3, ['os' => 'baz'])->create();
        Url::factory()->for($user, 'author')->hasVisits(4, ['os' => 'foo'])->create();
        Url::factory()->hasVisits(7, ['os' => 'Linux'])->create(); // From other users

        // Get the top operating systems for the authenticated user
        $topOS = Visit::getTopOperatingSystems($user, limit: 2);

        // Assertions
        $this->assertCount(2, $topOS);

        // Check if the operating systems are ordered correctly by visit count
        $this->assertEquals('foo', $topOS[0]->os);
        $this->assertEquals(9, $topOS[0]->total);
        $this->assertEquals('bar', $topOS[1]->os);
        $this->assertEquals(6, $topOS[1]->total);

        $this->assertNotContains('baz', $topOS->pluck('os')->toArray());
        $this->assertNotContains('Linux', $topOS->pluck('os')->toArray());
    }

    public function test_get_top_operating_systems_for_auth_user_with_no_visits()
    {
        $user = User::factory()->create();
        $topOS = Visit::getTopOperatingSystems($user);
        $this->assertCount(0, $topOS);
    }
}
