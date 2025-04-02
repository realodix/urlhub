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
        $topReferrers = Visit::getTopReferrers();
        $this->assertEquals(2, $topReferrers->count());
    }

    public function test_get_top_referrers_for_auth_user()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create some URLs belonging to the user
        Url::factory()->for($user, 'author')->hasVisits(5, ['referer' => 'https://example.com'])->create();
        Url::factory()->for($user, 'author')->hasVisits(3, ['referer' => 'https://google.com'])->create();
        Url::factory()->for($user, 'author')->hasVisits(2, ['referer' => 'https://twitter.com'])->create();
        Url::factory()->for($user, 'author')->hasVisits(1, ['referer' => 'https://instagram.com'])->create();
        Url::factory()->for($user, 'author')->hasVisits(6, ['referer' => 'https://example.com'])->create();
        Url::factory()->for($user, 'author')->hasVisits(4, ['referer' => 'https://bing.com'])->create();
        Url::factory()->hasVisits(7, ['referer' => 'https://facebook.com'])->create(); // From other users

        // Get the top referrers for the authenticated user
        $topReferrers = Visit::getTopReferrers($user);

        // Assertions
        $this->assertCount(5, $topReferrers);

        // Check if the referrers are ordered correctly by visit count
        $this->assertEquals('https://example.com', $topReferrers[0]->referer);
        $this->assertEquals(11, $topReferrers[0]->total);
        $this->assertEquals('https://bing.com', $topReferrers[1]->referer);
        $this->assertEquals(4, $topReferrers[1]->total);
        $this->assertEquals('https://google.com', $topReferrers[2]->referer);
        $this->assertEquals(3, $topReferrers[2]->total);
        $this->assertEquals('https://twitter.com', $topReferrers[3]->referer);
        $this->assertEquals(2, $topReferrers[3]->total);
        $this->assertEquals('https://instagram.com', $topReferrers[4]->referer);
        $this->assertEquals(1, $topReferrers[4]->total);

        // Check if referrers from other users are not included
        $this->assertNotContains('https://facebook.com', $topReferrers->pluck('referer')->toArray());
    }

    public function test_get_top_referrers_for_auth_user_with_no_visits()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $topReferrers = Visit::getTopReferrers($user);
        $this->assertCount(0, $topReferrers);
    }

    public function test_get_top_browsers()
    {
        // Create some visits with different browsers
        Visit::factory()->count(5)->create(['browser' => 'Chrome']);
        Visit::factory()->count(3)->create(['browser' => 'Firefox']);
        Visit::factory()->count(2)->create(['browser' => 'Safari']);
        Visit::factory()->count(1)->create(['browser' => 'Edge']);
        Visit::factory()->count(4)->create(['browser' => 'Opera']);
        Visit::factory()->count(6)->create(['browser' => 'Internet Explorer']);
        Visit::factory()->count(7)->create(['browser' => 'Chrome']);

        // Get the top browsers
        $topBrowsers = Visit::getTopBrowsers();

        // Assertions
        $this->assertCount(5, $topBrowsers);

        // Check if the browsers are ordered correctly by visit count
        $this->assertEquals('Chrome', $topBrowsers[0]->browser);
        $this->assertEquals(12, $topBrowsers[0]->total);
        $this->assertEquals('Internet Explorer', $topBrowsers[1]->browser);
        $this->assertEquals(6, $topBrowsers[1]->total);
        $this->assertEquals('Opera', $topBrowsers[2]->browser);
        $this->assertEquals(4, $topBrowsers[2]->total);
        $this->assertEquals('Firefox', $topBrowsers[3]->browser);
        $this->assertEquals(3, $topBrowsers[3]->total);
        $this->assertEquals('Safari', $topBrowsers[4]->browser);
        $this->assertEquals(2, $topBrowsers[4]->total);
    }

    public function test_get_top_browsers_for_auth_user()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create some URLs belonging to the user
        Url::factory()->for($user, 'author')->hasVisits(5, ['browser' => 'Chrome'])->create();
        Url::factory()->for($user, 'author')->hasVisits(3, ['browser' => 'Firefox'])->create();
        Url::factory()->for($user, 'author')->hasVisits(2, ['browser' => 'Edge'])->create();
        Url::factory()->for($user, 'author')->hasVisits(1, ['browser' => 'Opera'])->create();
        Url::factory()->for($user, 'author')->hasVisits(6, ['browser' => 'Chrome'])->create();
        Url::factory()->for($user, 'author')->hasVisits(4, ['browser' => 'Internet Explorer'])->create();
        Url::factory()->hasVisits(7, ['browser' => 'Safari'])->create(); // From other users

        // Get the top browsers for the authenticated user
        $topBrowsers = Visit::getTopBrowsers($user);

        // Assertions
        $this->assertCount(5, $topBrowsers);

        // Check if the browsers are ordered correctly by visit count
        $this->assertEquals('Chrome', $topBrowsers[0]->browser);
        $this->assertEquals(11, $topBrowsers[0]->total);
        $this->assertEquals('Internet Explorer', $topBrowsers[1]->browser);
        $this->assertEquals(4, $topBrowsers[1]->total);
        $this->assertEquals('Firefox', $topBrowsers[2]->browser);
        $this->assertEquals(3, $topBrowsers[2]->total);
        $this->assertEquals('Edge', $topBrowsers[3]->browser);
        $this->assertEquals(2, $topBrowsers[3]->total);
        $this->assertEquals('Opera', $topBrowsers[4]->browser);
        $this->assertEquals(1, $topBrowsers[4]->total);

        // Check if browsers from other users are not included
        $this->assertNotContains('Safari', $topBrowsers->pluck('browser')->toArray());
    }

    public function test_get_top_browsers_for_auth_user_with_no_visits()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $topBrowsers = Visit::getTopBrowsers($user);
        $this->assertCount(0, $topBrowsers);
    }

    public function test_get_top_operating_systems()
    {
        // Create some visits with different operating systems
        Visit::factory()->count(5)->create(['os' => 'Windows']);
        Visit::factory()->count(3)->create(['os' => 'macOS']);
        Visit::factory()->count(2)->create(['os' => 'Linux']);
        Visit::factory()->count(1)->create(['os' => 'Chrome OS']);
        Visit::factory()->count(4)->create(['os' => 'Android']);
        Visit::factory()->count(6)->create(['os' => 'iOS']);
        Visit::factory()->count(7)->create(['os' => 'Windows']);

        // Get the top operating systems
        $topOS = Visit::getTopOperatingSystems();

        // Assertions
        $this->assertCount(5, $topOS);

        // Check if the operating systems are ordered correctly by visit count
        $this->assertEquals('Windows', $topOS[0]->os);
        $this->assertEquals(12, $topOS[0]->total);
        $this->assertEquals('iOS', $topOS[1]->os);
        $this->assertEquals(6, $topOS[1]->total);
        $this->assertEquals('Android', $topOS[2]->os);
        $this->assertEquals(4, $topOS[2]->total);
        $this->assertEquals('macOS', $topOS[3]->os);
        $this->assertEquals(3, $topOS[3]->total);
        $this->assertEquals('Linux', $topOS[4]->os);
        $this->assertEquals(2, $topOS[4]->total);
    }

    public function test_get_top_operating_systems_for_auth_user()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create some URLs belonging to the user
        Url::factory()->for($user, 'author')->hasVisits(5, ['os' => 'Windows'])->create();
        Url::factory()->for($user, 'author')->hasVisits(3, ['os' => 'macOS'])->create();
        Url::factory()->for($user, 'author')->hasVisits(2, ['os' => 'Chrome OS'])->create();
        Url::factory()->for($user, 'author')->hasVisits(1, ['os' => 'Android'])->create();
        Url::factory()->for($user, 'author')->hasVisits(6, ['os' => 'iOS'])->create();
        Url::factory()->for($user, 'author')->hasVisits(4, ['os' => 'Windows'])->create();
        Url::factory()->hasVisits(7, ['os' => 'Linux'])->create(); // From other users

        // Get the top operating systems for the authenticated user
        $topOS = Visit::getTopOperatingSystems($user);

        // Assertions
        $this->assertCount(5, $topOS);

        // Check if the operating systems are ordered correctly by visit count
        $this->assertEquals('Windows', $topOS[0]->os);
        $this->assertEquals(9, $topOS[0]->total);
        $this->assertEquals('iOS', $topOS[1]->os);
        $this->assertEquals(6, $topOS[1]->total);
        $this->assertEquals('macOS', $topOS[2]->os);
        $this->assertEquals(3, $topOS[2]->total);
        $this->assertEquals('Chrome OS', $topOS[3]->os);
        $this->assertEquals(2, $topOS[3]->total);
        $this->assertEquals('Android', $topOS[4]->os);
        $this->assertEquals(1, $topOS[4]->total);

        // Check if operating systems from other users are not included
        $this->assertNotContains('Linux', $topOS->pluck('os')->toArray());
    }

    public function test_get_top_operating_systems_for_auth_user_with_no_visits()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $topOS = Visit::getTopOperatingSystems($user);
        $this->assertCount(0, $topOS);
    }
}
