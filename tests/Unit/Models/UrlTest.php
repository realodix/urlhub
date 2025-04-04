<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('model')]
class UrlTest extends TestCase
{
    private Url $url;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url;
        $this->visit = new Visit;
    }

    public function testFactory(): void
    {
        $m = Url::factory()->guest()->create();

        $this->assertSame(Url::GUEST_ID, $m->user_id);
        $this->assertSame(\App\Enums\UserType::Guest, $m->user_type);
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Url model must have a relationship with User model as one to many.
     * This test will check if the relationship exists.
     */
    #[PHPUnit\Test]
    public function belongsToUserModel(): void
    {
        $url = Url::factory()->create();

        $this->assertEquals(1, $url->author->count());
        $this->assertInstanceOf(User::class, $url->author);
    }

    /**
     * Url model must have a relationship with Visit model as one to many.
     * This test will check if the relationship exists.
     */
    #[PHPUnit\Test]
    public function hasManyVisitModel(): void
    {
        $v = Visit::factory()->create();

        $this->assertTrue($v->url()->exists());
        $this->assertInstanceOf(Url::class, $v->url);
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * The default guest id must be GUEST_ID.
     *
     * @see App\Models\Url::userId()
     */
    #[PHPUnit\Test]
    #[PHPUnit\TestWith([null])]
    #[PHPUnit\TestWith([0])]
    public function setUserIdAttributeMustBeGuestId($value): void
    {
        $url = Url::factory()->create(['user_id' => $value]);

        $this->assertSame(Url::GUEST_ID, $url->user_id);
    }

    /**
     * @see App\Models\Url::destination()
     */
    #[PHPUnit\Test]
    public function setLongUrlAttribute(): void
    {
        $url = Url::factory()->create(['destination' => 'http://example.com/']);

        $expected = $url->destination;
        $actual = 'http://example.com';
        $this->assertSame($expected, $actual);
    }

    /**
     * @see App\Models\Url::shortUrl()
     */
    #[PHPUnit\Test]
    public function getShortUrlAttribute(): void
    {
        $url = Url::factory()->create();
        $url->where('user_id', $url->author->id)->first();

        $expected = $url->short_url;
        $actual = url('/'.$url->keyword);
        $this->assertSame($expected, $actual);
    }

    /**
     * @see App\Models\Url::title()
     */
    public function testSetTitleLength(): void
    {
        $lengthLimit = Url::TITLE_LENGTH;

        $url = Url::factory()->create(['title' => str_repeat('a', $lengthLimit)]);
        $this->assertEquals($lengthLimit, strlen($url->title));

        $url = Url::factory()->create(['title' => str_repeat('a', $lengthLimit - 10)]);
        $this->assertLessThan($lengthLimit, strlen($url->title));

        $url = Url::factory()->create(['title' => str_repeat('a', $lengthLimit + 10)]);
        $this->assertEquals($lengthLimit, strlen($url->title));
    }

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

    public function testKeywordColumnIsCaseSensitive(): void
    {
        $url_1 = Url::factory()->create(['keyword' => 'foo', 'destination' => 'https://example.com']);
        $url_2 = Url::factory()->create(['keyword' => 'Foo', 'destination' => 'https://example.org']);

        $dest_1 = $url_1->where('keyword', 'foo')->first();
        $dest_2 = $url_2->where('keyword', 'Foo')->first();

        $this->assertSame('https://example.com', $dest_1->destination);
        $this->assertSame('https://example.org', $dest_2->destination);
    }

    #[PHPUnit\Test]
    public function getWebTitle(): void
    {
        settings()->fill(['retrieve_web_title' => false])->save();
        $actual = $this->url->getWebTitle('https://example123456789.com');
        $this->assertSame(null, $actual);

        settings()->fill(['retrieve_web_title' => true])->save();
        $actual = $this->url->getWebTitle('https://example123456789.com');
        $this->assertSame(null, $actual);
        $actual = $this->url->getWebTitle('whatsapp://send?text=WHATEVER_YOU_WANT');
        $this->assertSame(null, $actual);
    }

    #[PHPUnit\Test]
    public function authUserLinks(): void
    {
        $user = $this->basicUser();
        $nCurrentUser = 8;
        $nUser = 6;

        Url::factory()->count($nCurrentUser)->create(['user_id' => $user->id]);
        Url::factory()->count($nUser)->create();

        $this->actingAs($user);
        $this->assertSame($nCurrentUser, $this->url->authUserLinks());
        $this->assertSame($nUser + $nCurrentUser, $this->url->count());
    }

    #[PHPUnit\Test]
    public function userLinks(): void
    {
        $nUser = 6;
        $nGuest = 4;

        Url::factory()->count($nUser)->create();
        Url::factory()->count($nGuest)->guest()->create();

        $this->assertSame($nUser, $this->url->userLinks());
        $this->assertSame($nUser + $nGuest, $this->url->count());
    }

    #[PHPUnit\Test]
    public function guestLinks(): void
    {
        $nUser = 6;
        $nGuest = 4;

        Url::factory()->count($nUser)->create();
        Url::factory()->count($nGuest)->guest()->create();

        $this->assertSame($nGuest, $this->url->guestLinks());
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
        $topUrls = Url::getTopUrlsByVisits();

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
    public function getTopUrlsByVisitsForAuthUser(): void
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
        $topUrls = Url::getTopUrlsByVisits($user, limit: 2);

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
