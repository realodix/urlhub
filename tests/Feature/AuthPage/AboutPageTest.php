<?php

namespace Tests\Feature\AuthPage;

use App\Enums\UserType;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use App\Services\KeyGeneratorService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('auth-page')]
class AboutPageTest extends TestCase
{
    private Url $url;

    private User $user;

    private Visit $visit;

    private KeyGeneratorService $keyGen;

    const USER_COUNT = 1;
    const USER_GUEST_COUNT = 1;

    // ..
    const URL_COUNT = 2;
    const USER_URL_COUNT = 1;
    const USER_LINK_VISIT_COUNT = 4;
    const GUEST_URL_COUNT = 1;
    const GUEST_LINK_VISIT_COUNT = 4;

    // ..
    const VISIT_COUNT = 8;
    const USER_VISIT_COUNT = 2;
    const GUEST_VISIT_COUNT = 6;
    const UNIQUE_GUEST_VISIT_COUNT = 3;

    // ..
    const KEYWORD_COUNT = 1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url;
        $this->user = new User;
        $this->visit = new Visit;
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
            ->create(['user_type' => UserType::Bot, 'user_uid' => '8c40219a46b9f81b']);

        Visit::factory()->for($guestLink)->create();
        Visit::factory()->for($guestLink)->guest()
            ->create(['user_uid' => 'foo']);
        Visit::factory()->for($guestLink)->guest()
            ->create(['user_uid' => 'bar']);
        Visit::factory()->for($guestLink)
            ->create(['user_type' => UserType::Bot, 'user_uid' => '8c40219a46b9f81b']);
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
     * @see App\Http\Controllers\Dashboard\DashboardController::aboutView()
     */
    #[PHPUnit\Test]
    public function auAdminCanAccessThisPage(): void
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
     * @see App\Http\Controllers\Dashboard\DashboardController::aboutView()
     */
    #[PHPUnit\Test]
    public function auNormalUserCantAccessThisPage(): void
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
    public function guestUserCount(): void
    {
        $this->assertSame(self::USER_GUEST_COUNT, $this->user->totalGuestUsers());
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
    public function userUrlCount(): void
    {
        $this->assertSame(self::USER_URL_COUNT, $this->url->userUrlCount());
    }

    #[PHPUnit\Test]
    public function guestUserUrlCount(): void
    {
        $this->assertSame(self::GUEST_URL_COUNT, $this->url->guestUserUrlCount());
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
    public function userLinkVisitCount(): void
    {
        $this->assertSame(self::USER_LINK_VISIT_COUNT, $this->visit->userLinkVisitCount());
    }

    #[PHPUnit\Test]
    public function guestUserLinkVisitCount(): void
    {
        $this->assertSame(self::GUEST_LINK_VISIT_COUNT, $this->visit->guestUserLinkVisitCount());
    }

    #[PHPUnit\Test]
    public function userVisitCount(): void
    {
        $this->assertSame(self::USER_VISIT_COUNT, $this->visit->userVisitCount());
    }

    #[PHPUnit\Test]
    public function guestVisitCount(): void
    {
        $this->assertSame(self::GUEST_VISIT_COUNT, $this->visit->guestVisitCount());
    }

    #[PHPUnit\Test]
    public function uniqueGuestVisitCount(): void
    {
        $this->assertSame(self::UNIQUE_GUEST_VISIT_COUNT, $this->visit->uniqueGuestVisitCount());
    }

    /*
    |--------------------------------------------------------------------------
    | Random String
    |--------------------------------------------------------------------------
    */

    #[PHPUnit\Test]
    public function keywordCount(): void
    {
        $this->assertSame(self::KEYWORD_COUNT, $this->keyGen->keywordCount());
    }
}
