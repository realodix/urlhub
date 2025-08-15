<?php

namespace Tests\Feature\Config;

use App\Enums\UserType;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use App\Services\KeyGeneratorService;
use App\Services\LinkService;
use App\Services\UserService;
use App\Services\VisitService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('config')]
#[PHPUnit\Group('auth-page')]
class AboutPageTest extends TestCase
{
    private Url $url;

    private User $user;

    private Visit $visit;

    private UserService $userService;

    private KeyGeneratorService $keyGen;

    private LinkService $linkService;

    const USER_COUNT = 1;
    const USER_GUEST_COUNT = 1;

    // ..
    const URL_COUNT = 2;
    const USER_LINKS = 1;
    const USER_LINK_VISITS = 4;
    const GUEST_LINKS = 1;
    const GUEST_LINK_VISITS = 4;

    // ..
    const VISIT_COUNT = 8;
    const USER_VISITS = 2;
    const GUEST_VISITS = 6;

    // ..
    const VISITORS = 5;
    const VISITOR_USER = 2;
    const VISITOR_GUEST = 3;

    // ..
    const KEYWORD_COUNT = 1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url;
        $this->user = new User;
        $this->visit = new Visit;

        $this->userService = app(UserService::class);
        $this->linkService = app(LinkService::class);
        $this->visitService = app(VisitService::class);
        $this->keyGen = app(KeyGeneratorService::class);

        // URL
        $userLink = Url::factory()->create();
        $guestLink = Url::factory()->guest()->create([
            'keyword' => 'veerryyyylonngggggkeyword',
        ]);

        // Visit
        Visit::factory()->for($userLink)->create();
        Visit::factory()->for($userLink)->guest()
            ->create(['user_uid' => 'foo']);
        Visit::factory()->for($userLink)->guest()
            ->create(['user_uid' => 'bar']);
        Visit::factory()->for($userLink)
            ->create(['user_uid' => 'baz', 'user_type' => UserType::Bot]);

        Visit::factory()->for($guestLink)->create();
        Visit::factory()->for($guestLink)->guest()
            ->create(['is_first_click' => false, 'user_uid' => 'foo']);
        Visit::factory()->for($guestLink)->guest()
            ->create(['is_first_click' => false, 'user_uid' => 'bar']);
        Visit::factory()->for($guestLink)
            ->create(['is_first_click' => false, 'user_uid' => 'baz', 'user_type' => UserType::Bot]);
    }

    /*
    |--------------------------------------------------------------------------
    | Access Policy
    |--------------------------------------------------------------------------
    */

    /**
     * Test that an admin user can access the about page.
     *
     * This test simulates an admin user trying to access the about page, verifies
     * that the operation is successful by checking for an ok response.
     *
     * @see \App\Http\Controllers\Dashboard\DashboardController::aboutView()
     */
    #[PHPUnit\Test]
    public function access_AboutPage_Admin_WillBeOk(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('dboard.about'));
        $response->assertOk();
    }

    /**
     * Test that a normal user cannot access the about page.
     *
     * This test simulates a normal user attempting to access the about page
     * and verifies that access is forbidden by checking for a forbidden
     * response.
     *
     * @see \App\Http\Controllers\Dashboard\DashboardController::aboutView()
     */
    #[PHPUnit\Test]
    public function access_AboutPage_BasicUser_WillBeForbidden(): void
    {
        $response = $this->actingAs($this->basicUser())
            ->get(route('dboard.about'));
        $response->assertForbidden();
    }

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    #[PHPUnit\Test]
    public function userCount(): void
    {
        $this->assertSame(self::USER_COUNT, $this->user->count());
    }

    #[PHPUnit\Test]
    public function guestUsers(): void
    {
        $this->assertSame(self::USER_GUEST_COUNT, $this->userService->guestUsers());
    }

    /*
    |--------------------------------------------------------------------------
    | Link
    |--------------------------------------------------------------------------
    */

    #[PHPUnit\Test]
    public function urlCount(): void
    {
        $this->assertSame(self::URL_COUNT, $this->url->count());
    }

    #[PHPUnit\Test]
    public function userLinks(): void
    {
        $this->assertSame(self::USER_LINKS, $this->linkService->userLinks());
    }

    #[PHPUnit\Test]
    public function guestLinks(): void
    {
        $this->assertSame(self::GUEST_LINKS, $this->linkService->guestLinks());
    }

    /*
    |--------------------------------------------------------------------------
    | Visit
    |--------------------------------------------------------------------------
    */

    #[PHPUnit\Test]
    public function visitCount(): void
    {
        $this->assertSame(self::VISIT_COUNT, $this->visit->count());
    }

    #[PHPUnit\Test]
    public function visitsOnUserLinks(): void
    {
        $this->assertSame(self::USER_LINK_VISITS, $this->visitService->visitsOnUserLinks());
    }

    #[PHPUnit\Test]
    public function visitsOnGuestLinks(): void
    {
        $this->assertSame(self::GUEST_LINK_VISITS, $this->visitService->visitsOnGuestLinks());
    }

    #[PHPUnit\Test]
    public function userVisits(): void
    {
        $this->assertSame(self::USER_VISITS, $this->visitService->userVisits());
    }

    #[PHPUnit\Test]
    public function guestVisits(): void
    {
        $this->assertSame(self::GUEST_VISITS, $this->visitService->guestVisits());
    }

    #[PHPUnit\Test]
    public function visitors(): void
    {
        $this->assertSame(self::VISITORS, $this->visitService->visitors());
    }

    #[PHPUnit\Test]
    public function userVisitors(): void
    {
        $this->assertSame(self::VISITOR_USER, $this->visitService->userVisitors());
    }

    #[PHPUnit\Test]
    public function guestVisitors(): void
    {
        $this->assertSame(self::VISITOR_GUEST, $this->visitService->guestVisitors());
    }

    /*
    |--------------------------------------------------------------------------
    | Random String
    |--------------------------------------------------------------------------
    */

    #[PHPUnit\Test]
    public function standardKeywordSpaceUsed(): void
    {
        $this->assertSame(self::KEYWORD_COUNT, $this->keyGen->standardKeywordSpaceUsed());
    }
}
