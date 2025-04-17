<?php

namespace Tests\Unit\Livewire;

use App\Livewire\Chart\LinkVisitPerMonthChart;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

class LinkVisitPerMonthChartTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo('2024-12-31');
    }

    protected function tearDown(): void
    {
        $this->travelBack();
        parent::tearDown();
    }

    private function chartComponent($model = null)
    {
        return Livewire::test(LinkVisitPerMonthChart::class, ['model' => $model])
            ->instance();
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_all_visits()
    {
        // Arrange
        Visit::factory()->create(['created_at' => Carbon::now()->startOfMonth()]);
        Visit::factory()->create(['created_at' => Carbon::now()->subMonths(2)->startOfMonth()]);
        Visit::factory()->guest()->create(['created_at' => Carbon::now()->subMonths(2)->startOfMonth()]);

        $chartData = $this->chartComponent()->chartData();
        $period = app(LinkVisitPerMonthChart::class)->period();

        // Assert
        $this->assertCount($period->count(), $chartData);
        $this->assertEquals(1, $chartData[$period->count() - 1]); // this month
        $this->assertEquals(0, $chartData[$period->count() - 2]); // 1 month ago
        $this->assertEquals(2, $chartData[$period->count() - 3]); // 2 months ago
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_user()
    {
        $user = User::factory()->create();
        $url1 = Url::factory()->for($user, 'author')->create();
        $url2 = Url::factory()->create(); // Another user's URL
        // This month
        Visit::factory()->for($url1)->create(['created_at' => Carbon::now()->startOfMonth()]);
        Visit::factory()->for($url2)->create(['created_at' => Carbon::now()->startOfMonth()]);
        // 1 months ago
        Visit::factory()->for($url1)->count(2)->create(['created_at' => Carbon::now()->subMonths(2)->startOfMonth()]);
        Visit::factory()->for($url2)->create(['created_at' => Carbon::now()->subMonths(2)->startOfMonth()]);

        $chartData = $this->chartComponent($user)->chartData();
        $period = app(LinkVisitPerMonthChart::class)->period();

        // Assert
        $this->assertCount($period->count(), $chartData);
        $this->assertEquals(1, $chartData[$period->count() - 1]); // this month
        $this->assertEquals(2, $chartData[$period->count() - 3]); // 2 months ago
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_url()
    {
        $url = Url::factory()->create();
        // This month
        Visit::factory()->for($url)->create(['created_at' => Carbon::now()->startOfMonth()]);
        Visit::factory()->create(['created_at' => Carbon::now()->startOfMonth()]);
        // 1 months ago
        Visit::factory()->for($url)->count(2)->create(['created_at' => Carbon::now()->subMonths(2)->startOfMonth()]);
        Visit::factory()->create(['created_at' => Carbon::now()->subMonths(2)->startOfMonth()]);

        $chartData = $this->chartComponent($url)->chartData();
        $period = app(LinkVisitPerMonthChart::class)->period();

        // Assert
        $this->assertCount($period->count(), $chartData); // 1 year = 12 months
        $this->assertEquals(1, $chartData[$period->count() - 1]); // this month
        $this->assertEquals(2, $chartData[$period->count() - 3]); // 2 months ago
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_unique_visitors_all()
    {
        $period = app(LinkVisitPerMonthChart::class)->period();
        $now = Carbon::now(); // 2024-12-31 10:00:00
        $thisMonthDate = $now->copy()->startOfMonth()->addHours(1); // Dec 1, 2024 01:00:00
        $oneMonthAgoDate = $now->copy()->subMonthNoOverflow()->startOfMonth()->addHours(1); // Nov 1, 2024 01:00:00
        $twoMonthsAgoDate = $now->copy()->subMonthsNoOverflow(2)->startOfMonth()->addHours(1); // Oct 1, 2024 01:00:00

        // Arrange: Visits with duplicate UIDs within the same month

        // Arrange: Visits with duplicate UIDs within the same month
        // This month (Dec 2024): uid1 (2x), uid2 (1x) -> Expected Unique: 2
        Visit::factory()->create(['created_at' => $thisMonthDate, 'user_uid' => 'visitor-mo-1']);
        Visit::factory()->create(['created_at' => $thisMonthDate->copy()->addDay(), 'user_uid' => 'visitor-mo-1']);
        Visit::factory()->create(['created_at' => $thisMonthDate->copy()->addDays(2), 'user_uid' => 'visitor-mo-2']);
        // One month ago (Nov 2024): uid5 (2x) -> Expected Unique: 1
        Visit::factory()->create(['created_at' => $oneMonthAgoDate, 'user_uid' => 'visitor-mo-5']);
        Visit::factory()->create(['created_at' => $oneMonthAgoDate->copy()->addDay(), 'user_uid' => 'visitor-mo-5']);
        // Two months ago (Oct 2024): uid1 (1x), uid3 (1x), uid3 (1x) -> Expected Unique: 2
        Visit::factory()->create(['created_at' => $twoMonthsAgoDate, 'user_uid' => 'visitor-mo-1']);
        Visit::factory()->guest()->create(['created_at' => $twoMonthsAgoDate->copy()->addDay(), 'user_uid' => 'visitor-mo-3']);
        Visit::factory()->guest()->create(['created_at' => $twoMonthsAgoDate->copy()->addDays(2), 'user_uid' => 'visitor-mo-3']);
        // Visit outside period (should be ignored)
        Visit::factory()->create(['created_at' => $period->getStartDate()->copy()->subMonth(), 'user_uid' => 'visitor-mo-4']);

        // Act
        $chartData = $this->chartComponent()->chartData(visitor: true);

        // Assert
        $this->assertCount($period->count(), $chartData); // Should be 13 for 1 year + current month
        // Last index is 'this month' (Dec 2024)
        $this->assertEquals(2, $chartData[$period->count() - 1], 'Unique visitors count for this month mismatch.');
        // Second to last index is '1 month ago' (Nov 2024 - should be 1)
        $this->assertEquals(1, $chartData[$period->count() - 2], 'Unique visitors count for 1 month ago mismatch.');
        // Third to last index is '2 months ago' (Oct 2024)
        $this->assertEquals(2, $chartData[$period->count() - 3], 'Unique visitors count for 2 months ago mismatch.');
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_unique_visitors_user()
    {
        $period = app(LinkVisitPerMonthChart::class)->period();
        $thisMonthStart = Carbon::now()->startOfMonth();
        $twoMonthsAgoStart = Carbon::now()->subMonths(2)->startOfMonth();

        $user = User::factory()->create();
        $userUrl = Url::factory()->for($user, 'author')->create();
        $otherUrl = Url::factory()->create();

        // Arrange: Visits for the specific user's URL
        // This month: uid1 (2x), uid2 (1x) on userUrl -> Expected Unique for user: 2
        Visit::factory()->for($userUrl)->create(['created_at' => $thisMonthStart, 'user_uid' => 'user-visitor-mo-1']);
        Visit::factory()->for($userUrl)->create(['created_at' => $thisMonthStart->copy()->addDay(), 'user_uid' => 'user-visitor-mo-1']);
        Visit::factory()->for($userUrl)->create(['created_at' => $thisMonthStart->copy()->addDays(2), 'user_uid' => 'user-visitor-mo-2']);
        // Two months ago: uid1 (1x) on userUrl -> Expected Unique for user: 1
        Visit::factory()->for($userUrl)->create(['created_at' => $twoMonthsAgoStart, 'user_uid' => 'user-visitor-mo-1']);
        // Visits for other URL or outside period (should be ignored)
        Visit::factory()->for($otherUrl)->create(['created_at' => $thisMonthStart, 'user_uid' => 'other-visitor-mo-1']);
        Visit::factory()->guest()->create(['created_at' => $twoMonthsAgoStart, 'user_uid' => 'guest-visitor-mo']);
        Visit::factory()->for($userUrl)->create(['created_at' => $period->getStartDate()->copy()->subMonth(), 'user_uid' => 'user-visitor-mo-1']);

        // Act
        $chartData = $this->chartComponent($user)->chartData(visitor: true);

        // Assert
        $this->assertCount($period->count(), $chartData);
        $this->assertEquals(2, $chartData[$period->count() - 1], 'Unique visitors count for user this month mismatch.');
        $this->assertEquals(0, $chartData[$period->count() - 2], 'Unique visitors count for user 1 month ago mismatch.');
        $this->assertEquals(1, $chartData[$period->count() - 3], 'Unique visitors count for user 2 months ago mismatch.');
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_unique_visitors_url()
    {
        $period = app(LinkVisitPerMonthChart::class)->period();
        $thisMonthStart = Carbon::now()->startOfMonth();
        $twoMonthsAgoStart = Carbon::now()->subMonths(2)->startOfMonth();

        $targetUrl = Url::factory()->create();
        $otherUrl = Url::factory()->create();

        // Arrange: Visits for the specific URL
        // This month: uid1 (2x), uid2 (1x) on targetUrl -> Expected Unique for URL: 2
        Visit::factory()->for($targetUrl)->create(['created_at' => $thisMonthStart, 'user_uid' => 'url-visitor-mo-1']);
        Visit::factory()->for($targetUrl)->create(['created_at' => $thisMonthStart->copy()->addDay(), 'user_uid' => 'url-visitor-mo-1']);
        Visit::factory()->for($targetUrl)->create(['created_at' => $thisMonthStart->copy()->addDays(2), 'user_uid' => 'url-visitor-mo-2']);
        // Two months ago: uid1 (1x) on targetUrl -> Expected Unique for URL: 1
        Visit::factory()->for($targetUrl)->create(['created_at' => $twoMonthsAgoStart, 'user_uid' => 'url-visitor-mo-1']);
        // Visits for other URL or outside period (should be ignored)
        Visit::factory()->for($otherUrl)->create(['created_at' => $thisMonthStart, 'user_uid' => 'other-visitor-mo-1']);
        Visit::factory()->guest()->create(['created_at' => $twoMonthsAgoStart, 'user_uid' => 'guest-visitor-mo']);
        Visit::factory()->for($targetUrl)->create(['created_at' => $period->getStartDate()->copy()->subMonth(), 'user_uid' => 'url-visitor-mo-1']);

        // Act
        $chartData = $this->chartComponent($targetUrl)->chartData(visitor: true);

        // Assert
        $this->assertCount($period->count(), $chartData);
        $this->assertEquals(2, $chartData[$period->count() - 1], 'Unique visitors count for URL this month mismatch.');
        $this->assertEquals(0, $chartData[$period->count() - 2], 'Unique visitors count for URL 1 month ago mismatch.');
        $this->assertEquals(1, $chartData[$period->count() - 3], 'Unique visitors count for URL 2 months ago mismatch.');
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_label()
    {
        $chartLabel = $this->chartComponent()->chartLabel();

        // Assert
        $this->assertCount(13, $chartLabel); // 1 year + this month
        $this->assertEquals('Dec 2023', $chartLabel[0]);
        $this->assertEquals('Dec 2024', $chartLabel[12]);
    }
}
