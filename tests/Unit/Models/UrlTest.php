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

    /**
     * The default guest id must be null.
     */
    #[PHPUnit\Test]
    public function defaultGuestId(): void
    {
        $longUrl = 'https://example.com';

        $this->post(route('su_create'), ['long_url' => $longUrl]);

        $url = Url::whereDestination($longUrl)->first();

        $this->assertSame(Url::GUEST_ID, $url->user_id);
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    #[PHPUnit\Test]
    public function setUserIdAttributeMustBeNull(): void
    {
        $url = Url::factory()->create(['user_id' => 0]);

        $this->assertSame(null, $url->user_id);
    }

    #[PHPUnit\Test]
    public function setLongUrlAttribute(): void
    {
        $url = Url::factory()->create(['destination' => 'http://example.com/']);

        $expected = $url->destination;
        $actual = 'http://example.com';
        $this->assertSame($expected, $actual);
    }

    #[PHPUnit\Test]
    public function getShortUrlAttribute(): void
    {
        $url = Url::factory()->create();
        $url->whereUserId($url->author->id)->first();

        $expected = $url->short_url;
        $actual = url('/'.$url->keyword);
        $this->assertSame($expected, $actual);
    }

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

    #[PHPUnit\Test]
    public function setMetaTitleAttributeWhenWebTitleSetToFalse(): void
    {
        config(['urlhub.web_title' => false]);

        $url = Url::factory()->create(['destination' => 'http://example.com/']);

        $this->assertSame('No Title', $url->title);
    }

    /**
     * Get clicks attribute
     */
    #[PHPUnit\Test]
    public function getClicksAttribute(): void
    {
        $url = Url::factory()->create();

        Visit::factory()->create(['url_id' => $url->id]);

        $this->assertSame(1, $url->clicks);
    }

    /**
     * Get uniqueClicks attribute
     */
    #[PHPUnit\Test]
    public function getUniqueClicksAttribute(): void
    {
        $url = Url::factory()->create();

        Visit::factory()->create(['url_id' => $url->id]);

        Visit::factory()->create([
            'url_id' => $url->id,
            'is_first_click' => false,
        ]);

        $this->assertSame(1, $url->uniqueClicks);
    }

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

    #[PHPUnit\Test]
    public function getWebTitle(): void
    {
        $expected = 'example123456789.com - Untitled';
        $actual = $this->url->getWebTitle('https://example123456789.com');
        $this->assertSame($expected, $actual);

        $expected = 'www.example123456789.com - Untitled';
        $actual = $this->url->getWebTitle('https://www.example123456789.com');
        $this->assertSame($expected, $actual);
    }

    /**
     * When config('urlhub.web_title') set `false`, title() should return
     * 'No Title' if the title is empty
     */
    #[PHPUnit\Test]
    public function getWebTitle_ShouldReturnNoTitle(): void
    {
        config(['urlhub.web_title' => false]);

        $expected = 'No Title';
        $actual = $this->url->getWebTitle('https://example123456789.com');
        $this->assertSame($expected, $actual);
    }

    /**
     * The number of shortened URLs that have been created by each User
     */
    #[PHPUnit\Test]
    public function numberOfUrl(): void
    {
        $user = $this->normalUser();

        Url::factory([
            'user_id' => $user->id,
        ])->create();

        $this->actingAs($user);
        $actual = $this->url->numberOfUrl();

        $this->assertSame(1, $actual);
    }

    /**
     * The total number of shortened URLs that have been created by all guests
     */
    #[PHPUnit\Test]
    public function numberOfUrlFromGuests(): void
    {
        Url::factory()->create(['user_id' => Url::GUEST_ID]);

        $actual = $this->url->numberOfUrlFromGuests();

        $this->assertSame(1, $actual);
    }

    #[PHPUnit\Test]
    public function numberOfClicks(): void
    {
        $v = Visit::factory()->create();

        Visit::factory()->create(['url_id' => $v->url->id]);

        $actual = $this->url->numberOfClicks($v->url->id);

        $this->assertSame(2, $actual);
    }

    /**
     * Total clicks on each shortened URL, but only count unique clicks
     */
    #[PHPUnit\Test]
    public function numberOfClicksAndUnique(): void
    {
        $v = Visit::factory()->create();

        Visit::factory()->create([
            'url_id' => $v->url->id,
            'is_first_click' => false,
        ]);

        $actual = $this->url->numberOfClicks($v->url->id, unique: true);

        $this->assertSame(1, $actual);
    }

    public function testKeywordColumnIsCaseSensitive(): void
    {
        $url_1 = Url::factory()->create(['keyword' => 'foo', 'destination' => 'https://example.com']);
        $url_2 = Url::factory()->create(['keyword' => 'Foo', 'destination' => 'https://example.org']);

        $dest_1 = $url_1->whereKeyword('foo')->first();
        $dest_2 = $url_2->whereKeyword('Foo')->first();

        $this->assertSame('https://example.com', $dest_1->destination);
        $this->assertSame('https://example.org', $dest_2->destination);
    }
}
