<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use Tests\TestCase;

class UrlTest extends TestCase
{
    private Url $url;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url;
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Url model must have a relationship with User model as one to many.
     * This test will check if the relationship exists.
     *
     * @test
     * @group u-model
     */
    public function belongsToUserModel(): void
    {
        $url = Url::factory()->create();

        $this->assertEquals(1, $url->author->count());
        $this->assertInstanceOf(User::class, $url->author);
    }

    /**
     * Url model must have a relationship with Visit model as one to many.
     * This test will check if the relationship exists.
     *
     * @test
     * @group u-model
     */
    public function hasManyVisitModel(): void
    {
        $v = Visit::factory()->create();

        $this->assertTrue($v->url()->exists());
        $this->assertInstanceOf(Url::class, $v->url);
    }

    /**
     * The default guest name must be Guest.
     *
     * @test
     * @group u-model
     */
    public function defaultGuestName(): void
    {
        $url = Url::factory()->create(['user_id' => Url::GUEST_ID]);

        $this->assertSame(Url::GUEST_NAME, $url->author->name);
    }

    /**
     * The default guest id must be null.
     *
     * @test
     * @group u-model
     */
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

    /**
     * @test
     * @group u-model
     */
    public function setUserIdAttributeMustBeNull(): void
    {
        $url = Url::factory()->create(['user_id' => 0]);

        $this->assertSame(null, $url->user_id);
    }

    /**
     * @test
     * @group u-model
     */
    public function setLongUrlAttribute(): void
    {
        $url = Url::factory()->create(['destination' => 'http://example.com/']);

        $expected = $url->destination;
        $actual = 'http://example.com';
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function getShortUrlAttribute(): void
    {
        $url = Url::factory()->create();
        $url->whereUserId($url->author->id)->first();

        $expected = $url->short_url;
        $actual = url('/'.$url->keyword);
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function setMetaTitleAttributeWhenWebTitleSetToFalse(): void
    {
        config(['urlhub.web_title' => false]);

        $url = Url::factory()->create(['destination' => 'http://example.com/']);

        $this->assertSame('No Title', $url->title);
    }

    /**
     * Get clicks attribute
     *
     * @test
     * @group u-model
     */
    public function getClicksAttribute(): void
    {
        $url = Url::factory()->create();

        Visit::factory()->create(['url_id' => $url->id]);

        $this->assertSame(1, $url->clicks);
    }

    /**
     * Get uniqueClicks attribute
     *
     * @test
     * @group u-model
     */
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

    /**
     * The number of shortened URLs that have been created by each User
     *
     * @test
     * @group u-model
     */
    public function numberOfUrls(): void
    {
        $url = Url::factory()->create();

        $actual = $this->url->numberOfUrls($url->author->id);

        $this->assertSame(1, $actual);
    }

    /**
     * The total number of shortened URLs that have been created by all guests
     *
     * @test
     * @group u-model
     */
    public function numberOfUrlsByGuests(): void
    {
        Url::factory()->create(['user_id' => Url::GUEST_ID]);

        $actual = $this->url->numberOfUrlsByGuests();

        $this->assertSame(1, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function numberOfClicks(): void
    {
        $v = Visit::factory()->create();

        Visit::factory()->create(['url_id' => $v->url->id]);

        $actual = $this->url->numberOfClicks($v->url->id);

        $this->assertSame(2, $actual);
    }

    /**
     * Total clicks on each shortened URL, but only count unique clicks
     *
     * @test
     * @group u-model
     */
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

    /**
     * Total klik dari setiap shortened URLs yang dibuat oleh user tertentu
     *
     * @test
     * @group u-model
     */
    public function numberOfClicksPerAuthor(): void
    {
        $visit = Visit::factory()
            ->for(Url::factory())
            ->create();

        $expected = Visit::whereUrlId($visit->url->id)->count();
        $actual = $visit->url->numberOfClicksPerAuthor();

        $this->assertSame($expected, $actual);
        $this->assertSame(1, $actual);
    }

    /**
     * Total clicks on all short URLs from all guest users
     *
     * @test
     * @group u-model
     */
    public function numberOfClicksFromGuests(): void
    {
        $visit = Visit::factory()
            ->for(Url::factory()->create(['user_id' => Url::GUEST_ID]))
            ->create();

        $expected = Visit::whereUrlId($visit->url->id)->count();
        $actual = $this->url->numberOfClicksFromGuests();

        $this->assertSame(Url::GUEST_ID, $visit->url->user_id);
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @group u-model
     */
    public function totalClicks(): void
    {
        Visit::factory()->create();

        $actual = $this->url->totalClick();

        $this->assertSame(1, $actual);
    }
}
