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

    /**
     * The default guest id must be null.
     */
    #[PHPUnit\Test]
    public function defaultGuestId(): void
    {
        $longUrl = 'https://example.com';

        $this->post(route('link.create'), ['long_url' => $longUrl]);

        $url = Url::where('destination', $longUrl)->first();

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
        $url->where('user_id', $url->author->id)->first();

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
        settings()->fill(['retrieve_web_title' => false])->save();

        $url = Url::factory()->create(['destination' => 'http://example.com/']);

        $this->assertSame('No Title', $url->title);
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
        $expected = 'example123456789.com - Untitled';
        $actual = $this->url->getWebTitle('https://example123456789.com');
        $this->assertSame($expected, $actual);

        $expected = 'www.example123456789.com - Untitled';
        $actual = $this->url->getWebTitle('https://www.example123456789.com');
        $this->assertSame($expected, $actual);
    }

    /**
     * When `retrieve_web_title` set `false`, title() should return
     * 'No Title' if the title is empty.
     */
    #[PHPUnit\Test]
    public function getWebTitle_ShouldReturnNoTitle(): void
    {
        settings()->fill(['retrieve_web_title' => false])->save();

        $expected = 'No Title';
        $actual = $this->url->getWebTitle('https://example123456789.com');
        $this->assertSame($expected, $actual);
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
}
