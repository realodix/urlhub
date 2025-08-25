<?php

namespace Tests\Feature\FrontPage;

use App\Enums\UserType;
use App\Models\Url;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('front-page')]
class VisitTest extends TestCase
{
    #[PHPUnit\Test]
    public function urlRedirection(): void
    {
        $url = Url::factory()->create();

        $response = $this->get($url->keyword);
        $response->assertRedirect($url->destination)
            ->assertStatus(config('urlhub.redirection_status_code'));

        $this->assertCount(1, Visit::all());
    }

    /**
     * @see \App\Services\VisitService::create()
     * @see \App\Services\UserService::userType()
     */
    public function testUserVisitor(): void
    {
        $url = Url::factory()->create();

        $this->actingAs($this->basicUser())
            ->get($url->keyword);

        $visit = $url->visits()->first();
        $this->assertCount(1, Visit::all());
        $this->assertSame(UserType::User, $visit->user_type);
    }

    /**
     * @see \App\Services\VisitService::create()
     * @see \App\Services\UserService::userType()
     */
    #[PHPUnit\Test]
    public function userVisitorWithBotUserAgent(): void
    {
        settings()->fill(['track_bot_visits' => false])->save();
        $url = Url::factory()->create();

        $this->partialMock(CrawlerDetect::class)
            ->shouldReceive(['isCrawler' => true]);
        $this->actingAs($this->basicUser())
            ->get($url->keyword);

        $visit = $url->visits()->first();
        $this->assertCount(0, Visit::all());
        $this->assertSame(null, $visit?->user_type);
    }

    /**
     * @see \App\Services\VisitService::create()
     * @see \App\Services\UserService::userType()
     */
    public function testGuestVisitor(): void
    {
        $url = Url::factory()->create();

        $this->get($url->keyword);

        $visit = $url->visits()->first();
        $this->assertCount(1, Visit::all());
        $this->assertSame(UserType::Guest, $visit->user_type);
    }

    /**
     * @see \App\Services\VisitService::create()
     * @see \App\Services\UserService::userType()
     */
    public function testBotVisitor(): void
    {
        settings()->fill(['track_bot_visits' => true])->save();
        $url = Url::factory()->create();

        $this->partialMock(CrawlerDetect::class)
            ->shouldReceive(['isCrawler' => true]);
        $this->get($url->keyword);

        $visit = $url->visits()->first();
        $this->assertCount(1, Visit::all());
        $this->assertSame(UserType::Bot, $visit->user_type);
    }

    /**
     * @see \App\Services\VisitService::create()
     * @see \App\Services\UserService::userType()
     */
    #[PHPUnit\Test]
    public function botVisitorWithDisabledBotTracking(): void
    {
        settings()->fill(['track_bot_visits' => false])->save();
        $url = Url::factory()->create();

        $this->partialMock(CrawlerDetect::class)
            ->shouldReceive(['isCrawler' => true]);
        $this->get($url->keyword);

        $visit = $url->visits()->first();
        $this->assertCount(0, Visit::all());
        $this->assertSame(null, $visit?->user_type);
    }

    /**
     * @see \App\Services\VisitService::create()
     * @see \App\Services\VisitService::isFirstClick()
     */
    public function testIsFirstClick(): void
    {
        $url = Url::factory()->create();

        // First visit
        // Should fill is_first_click with true
        $this->get($url->keyword);
        $this->assertTrue($url->visits()->first()->is_first_click);

        // Second visit (same user) and so on
        // Should fill is_first_click with false
        $this->get($url->keyword);
        $visits = $url->visits()->get();
        $secondVisit = $visits->last();

        $this->assertCount(2, Visit::all());
        $this->assertFalse($secondVisit->is_first_click);
    }

    /*
    |-----------------------------------------------------------------
    | General
    |-----------------------------------------------------------------
    */

    /**
     * Tests that a link with a password:
     * - redirects to the password form page when the password is set
     * - redirects to the destination URL when the correct password is provided
     *
     * @see \App\Http\Controllers\RedirectController::__invoke()
     * @see \App\Http\Controllers\LinkController::password()
     * @see \App\Http\Controllers\LinkController::validatePassword()
     */
    #[PHPUnit\Test]
    public function linkWithPassword()
    {
        $url = Url::factory()->create(['password' => 'secret']);

        $response = $this->get($url->keyword);
        $response->assertRedirect(route('link.password', $url->keyword));

        // correct password
        $response = $this->from(route('link.password', $url->keyword))
            ->post(route('link.password', $url->keyword), ['password' => 'secret']);
        $response->assertRedirect($url->destination);
        // wrong password
        $response = $this->from(route('link.password', $url->keyword))
            ->post(route('link.password', $url->keyword), ['password' => 'wrong']);
        $response->assertRedirect(route('link.password', $url->keyword));
    }

    /**
     * @see \App\Http\Controllers\RedirectController::__invoke()
     */
    #[PHPUnit\Test]
    public function linkWithPassword_ownerOrAdminCanByPass()
    {
        // Owner
        $url = Url::factory()->create(['password' => 'secret']);
        $response = $this->actingAs($url->author)
            ->get($url->keyword);
        $response->assertRedirect($url->destination);

        // Admin
        $response = $this->actingAs($this->adminUser())
            ->get($url->keyword);
        $response->assertRedirect($url->destination);

        Auth::logout();

        // Other user cannot bypass
        $response = $this->actingAs($this->basicUser())
            ->get($url->keyword);
        $response->assertRedirect(route('link.password', $url->keyword));
    }

    /**
     * Tests opening the password form page:
     *
     * @see \App\Http\Controllers\RedirectController::__invoke()
     * @see \App\Http\Controllers\LinkController::password()
     */
    #[PHPUnit\Test]
    public function linkWithPassword_form()
    {
        // Password is set
        // form page must be shown when the link has a password
        $url = Url::factory()->create(['password' => 'secret']);
        $response = $this->get(route('link.password', $url->keyword));
        $response->assertViewIs('frontend.link-password');
        $response->assertViewHas('url', $url);

        // Password is not set
        // redirects to the link detail page
        $url = Url::factory()->create(['password' => null]);
        $response = $this->get(route('link.password', $url->keyword));
        $response->assertRedirect(route('link_detail', $url->keyword));
    }

    /**
     * @see \App\Http\Controllers\RedirectController::__invoke()
     */
    #[PHPUnit\Test]
    public function linkHasExpiredAfterSpecifiedDate()
    {
        // Test case 1: Redirect to landing page and do not count as a visit
        $url = Url::factory()->create(['expires_at' => now()->subSecond()]);
        $response = $this->get($url->keyword);
        $response->assertRedirectToRoute('link.expired', $url->keyword);
        $this->assertCount(0, $url->visits);

        // Test case 2: Redirect to the given url and do not count as a visit
        $url = Url::factory()->create([
            'expires_at' => now()->subSecond(),
            'expired_url' => 'https://example.com',
        ]);
        $response = $this->get($url->keyword);
        $response->assertRedirect($url->expired_url);
        $this->assertCount(0, $url->visits);
    }

    /**
     * @see \App\Http\Controllers\RedirectController::__invoke()
     */
    #[PHPUnit\Test]
    public function linkHasExpiredAfterClicks_NotFound()
    {
        $url = Url::factory()->create(['expired_clicks' => 1]);

        // Test case 1: link has not expired
        // Redirect to destination and count as a visit
        $response = $this->get($url->keyword);
        $response->assertRedirect($url->destination);
        $this->assertCount(1, $url->visits);

        // Test case 2: Expired
        // Redirect to landing page and do not count as a visit
        $response = $this->get($url->keyword);
        $response->assertRedirectToRoute('link.expired', $url->keyword);
        $this->assertCount(1, $url->visits);
    }

    /**
     * @see \App\Http\Controllers\RedirectController::__invoke()
     */
    #[PHPUnit\Test]
    public function linkHasExpiredAfterClicks_RedirectToExpiredUrl()
    {
        $url = Url::factory()->create([
            'expired_clicks' => 1,
            'expired_url' => 'https://example.com',
        ]);

        // Test case 1: link has not expired
        // Redirect to destination and count as a visit
        $response = $this->get($url->keyword);
        $response->assertRedirect($url->destination);
        $this->assertCount(1, $url->visits);

        // Test case 2: Expired
        // Redirect to the given url and do not count as a visit
        $response = $this->get($url->keyword);
        $response->assertRedirect('https://example.com');
        $this->assertCount(1, $url->visits);
    }

    /**
     * @see \App\Http\Controllers\RedirectController::__invoke()
     * @see \App\Http\Controllers\LinkController::expiredLink()
     */
    #[PHPUnit\Test]
    public function linkHasExpired_AccessLandingPage()
    {
        // Test case 1: link has expired
        $url = Url::factory()->create(['expires_at' => now()->subSecond()]);
        $response = $this->get(route('link.expired', $url));
        $response->assertViewIs('frontend.link-expired');

        // Test case 2: link has not expired
        $url = Url::factory()->create();
        $response = $this->get(route('link.expired', $url));
        $response->assertRedirect(route('link_detail', $url->keyword));
    }

    /**
     * When a link has a blacklisted domain, it should be redirected
     * to the landing page.
     *
     * @see \App\Http\Controllers\RedirectController::__invoke()
     */
    #[PHPUnit\Test]
    public function linkHasBlacklistedDomain()
    {
        // Test case 1: domain is not blacklisted
        $url = Url::factory()->create([
            'destination' => 'https://laravel.com/docs',
        ]);
        $response = $this->get($url->keyword);
        $response->assertStatus(config('urlhub.redirection_status_code'));

        // Test case 2: domain is blacklisted
        config(['urlhub.blacklist_domain' => ['laravel.com']]);
        $response = $this->get($url->keyword);
        $response->assertNotFound();
    }
}
