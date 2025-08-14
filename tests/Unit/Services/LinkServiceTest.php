<?php

namespace Tests\Unit\Services;

use App\Models\Url;
use App\Models\User;
use App\Services\LinkService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
class LinkServiceTest extends TestCase
{
    private Url $url;

    private LinkService $linkService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url;
        $this->linkService = app(LinkService::class);
    }

    #[PHPUnit\Test]
    public function getWebTitle(): void
    {
        settings()->fill(['autofill_link_title' => false])->save();
        $actual = $this->linkService->getWebTitle('https://example123456789.com');
        $this->assertSame(null, $actual);

        settings()->fill(['autofill_link_title' => true])->save();
        $actual = $this->linkService->getWebTitle('https://example123456789.com');
        $this->assertSame(null, $actual);
        $actual = $this->linkService->getWebTitle('whatsapp://send?text=WHATEVER_YOU_WANT');
        $this->assertSame(null, $actual);
    }

    #[PHPUnit\Test]
    public function userLinks(): void
    {
        $nUser = 6;
        $nGuest = 4;

        Url::factory()->count($nUser)->create();
        Url::factory()->count($nGuest)->guest()->create();

        $this->assertSame($nUser, $this->linkService->userLinks());
        $this->assertSame($nUser + $nGuest, $this->url->count());
    }

    #[PHPUnit\Test]
    public function guestLinks(): void
    {
        $nUser = 6;
        $nGuest = 4;

        Url::factory()->count($nUser)->create();
        Url::factory()->count($nGuest)->guest()->create();

        $this->assertSame($nGuest, $this->linkService->guestLinks());
        $this->assertSame($nUser + $nGuest, $this->url->count());
    }

    #[PHPUnit\Test]
    public function getTopUrlsByVisits(): void
    {
        // Create some URLs with varying numbers of visits
        $url1 = Url::factory()->hasVisits(3)->create();
        $url2 = Url::factory()->hasVisits(5)->create();
        $url3 = Url::factory()->hasVisits(1)->create();
        $url5 = Url::factory()->hasVisits(7)->create();
        $url6 = Url::factory()->hasVisits(2)->create();
        $url4 = Url::factory()->create(); // No visits

        // Get the top URLs by visits
        $topUrls = $this->linkService->getTopUrlsByVisits();

        // Assertions
        $this->assertCount(5, $topUrls);

        // Check if the URLs are ordered correctly by visit count
        $this->assertEquals($url5->id, $topUrls[0]->id);
        $this->assertEquals($url2->id, $topUrls[1]->id);
        $this->assertEquals($url1->id, $topUrls[2]->id);
        $this->assertEquals($url6->id, $topUrls[3]->id);
        $this->assertEquals($url3->id, $topUrls[4]->id);

        // Check if URLs without visits are not included
        $this->assertNotContains($url4->id, $topUrls->pluck('id')->toArray());
    }

    #[PHPUnit\Test]
    public function getTopUrlsByVisits_AuthUser(): void
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        // Create some URLs, some belonging to the user, some not
        $userUrl1 = Url::factory()->for($user, 'author')->hasVisits(3)->create();
        $userUrl2 = Url::factory()->for($user, 'author')->hasVisits(5)->create();
        $userUrl5 = Url::factory()->for($user, 'author')->hasVisits(8)->create();

        $otherUserUrl = Url::factory()->hasVisits(7)->create(); // Not owned by the user
        $userUrl4 = Url::factory()->for($user, 'author')->create(); // No visits

        // Get the top URLs by visits for the authenticated user
        $topUrls = $this->linkService->getTopUrlsByVisits($user, limit: 2);

        // Assertions
        $this->assertCount(2, $topUrls);

        // Check if the URLs are ordered correctly by visit count
        $this->assertEquals($userUrl5->id, $topUrls[0]->id);
        $this->assertEquals($userUrl2->id, $topUrls[1]->id);

        // Check limit
        $this->assertNotContains($userUrl1->id, $topUrls->pluck('id')->toArray());
        // Check if URLs from other users are not included
        $this->assertNotContains($otherUserUrl->id, $topUrls->pluck('id')->toArray());
        // Check if URLs without visits are not included
        $this->assertNotContains($userUrl4->id, $topUrls->pluck('id')->toArray());
    }
}
