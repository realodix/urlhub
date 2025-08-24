<?php

namespace Tests\Unit\Models;

use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use App\Rules\LinkRules;
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
    }

    public function testFactory(): void
    {
        $m = Url::factory()->guest()->create();

        $this->assertSame(User::GUEST_ID, $m->user_id);
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
     * @see \App\Models\Url::userId()
     */
    #[PHPUnit\TestWith([null])]
    #[PHPUnit\TestWith([0])]
    #[PHPUnit\Test]
    public function setUserIdAttributeMustBeGuestId($value): void
    {
        $url = Url::factory()->create(['user_id' => $value]);

        $this->assertSame(User::GUEST_ID, $url->user_id);
    }

    /**
     * @see \App\Models\Url::destination()
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
     * @see \App\Models\Url::shortUrl()
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
     * @see \App\Models\Url::title()
     */
    #[PHPUnit\Test]
    public function setTitleLength(): void
    {
        $lengthLimit = LinkRules::TITLE_MAX_LENGTH;

        $url = Url::factory()->create(['title' => str_repeat('a', $lengthLimit)]);
        $this->assertEquals($lengthLimit, strlen($url->title));

        $url = Url::factory()->create(['title' => str_repeat('a', $lengthLimit - 10)]);
        $this->assertLessThan($lengthLimit, strlen($url->title));

        $url = Url::factory()->create(['title' => str_repeat('a', $lengthLimit + 10)]);
        $this->assertEquals($lengthLimit, strlen($url->title));
    }

    /**
     * @see \App\Models\Url::expiredClicks()
     */
    #[PHPUnit\Test]
    public function setExpiredClicks(): void
    {
        $url = Url::factory()->create(['expired_clicks' => 0]);
        $this->assertNull($url->expired_clicks);
    }

    /**
     * @see \App\Models\Url::expiredUrl()
     */
    #[PHPUnit\Test]
    public function setExpiredUrl(): void
    {
        $url = Url::factory()->create([
            'expires_at' => null,
            'expired_clicks' => 1,
            'expired_url' => 'https://laravel.com/',
        ]);
        $this->assertSame('https://laravel.com/', $url->expired_url);

        // expired_url must be null if expired_clicks and expires_at are null
        $url = Url::factory()->create([
            'expires_at' => null,
            'expired_clicks' => null,
            'expired_url' => 'https://laravel.com/',
        ]);
        $this->assertNull($url->expired_url);
    }

    /**
     * @see \App\Models\Url::expiredNotes()
     */
    #[PHPUnit\Test]
    public function setExpiredNotes(): void
    {
        $url = Url::factory()->create([
            'expires_at' => null,
            'expired_clicks' => 1,
            'expired_notes' => 'laravel',
        ]);
        $this->assertSame('laravel', $url->expired_notes);

        // expired_notes must be null if expired_clicks and expires_at are null
        $url = Url::factory()->create([
            'expires_at' => null,
            'expired_clicks' => null,
            'expired_notes' => 'laravel',
        ]);
        $this->assertNull($url->expired_notes);
    }

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

    #[PHPUnit\Test]
    public function keywordColumnIsCaseSensitive(): void
    {
        $url_1 = Url::factory()->create(['keyword' => 'foo', 'destination' => 'https://example.com']);
        $url_2 = Url::factory()->create(['keyword' => 'Foo', 'destination' => 'https://example.org']);

        $dest_1 = $url_1->where('keyword', 'foo')->first();
        $dest_2 = $url_2->where('keyword', 'Foo')->first();

        $this->assertSame('https://example.com', $dest_1->destination);
        $this->assertSame('https://example.org', $dest_2->destination);
    }

    #[PHPUnit\Test]
    public function isExpired(): void
    {
        // Not expired - no limits set
        $url = Url::factory()->create([
            'expires_at' => null,
            'expired_clicks' => null,
        ]);
        $this->assertFalse($url->isExpired());

        // Not expired - expires_at is in the future
        $url = Url::factory()->create(['expires_at' => now()->addDay()]);
        $this->assertFalse($url->isExpired());

        // Not expired - clicks are less than expired_clicks
        $url = Url::factory()->create(['expired_clicks' => 2]);
        Visit::factory()->for($url)->create();
        $this->assertFalse($url->isExpired());

        // Expired - expires_at is in the past
        $url = Url::factory()->create(['expires_at' => now()->subDay()]);
        $this->assertTrue($url->isExpired());

        // Expired - clicks are equal to expired_clicks
        $url = Url::factory()->create(['expired_clicks' => 1]);
        Visit::factory()->for($url)->create();
        $this->assertTrue($url->isExpired());

        // Not expired - expired_clicks is 0
        $url = Url::factory()->create(['expired_clicks' => 0]);
        Visit::factory()->for($url)->create();
        $this->assertFalse($url->isExpired());
    }
}
