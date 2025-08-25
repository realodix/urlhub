<?php

namespace Tests\Unit\Livewire;

use App\Livewire\Chart\LinkVisitPerWeekChart;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

class LinkVisitPerWeekChartTest extends TestCase
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
        return Livewire::test(LinkVisitPerWeekChart::class, ['model' => $model])
            ->instance();
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_all_visits()
    {
        Visit::factory()->create(['created_at' => Carbon::now()->startOfWeek()]);
        Visit::factory()->create(['created_at' => Carbon::now()->subWeeks(2)->startOfWeek()]);
        Visit::factory()->guest()->create(['created_at' => Carbon::now()->subWeeks(2)->startOfWeek()]);

        $chartData = $this->chartComponent()->chartData();
        $period = app(LinkVisitPerWeekChart::class)->period();

        // Assert
        $this->assertCount($period->count(), $chartData);
        $this->assertEquals(1, $chartData[$period->count() - 1]); // this week
        $this->assertEquals(0, $chartData[$period->count() - 2]); // 1 weeks ago
        $this->assertEquals(2, $chartData[$period->count() - 3]); // 2 weeks ag
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_user()
    {
        $user = User::factory()->create();
        $url1 = Url::factory()->for($user, 'author')->create();
        $url2 = Url::factory()->create(); // Another user's URL
        // This week
        Visit::factory()->for($url1)->create(['created_at' => Carbon::now()->startOfWeek()]);
        Visit::factory()->for($url2)->create(['created_at' => Carbon::now()->startOfWeek()]);
        // 1 weeks ago
        Visit::factory()->for($url1)->count(2)->create(['created_at' => Carbon::now()->subWeeks(1)->startOfWeek()]);
        Visit::factory()->for($url2)->create(['created_at' => Carbon::now()->subWeek()->startOfWeek()]);

        $chartData = $this->chartComponent($user)->chartData();
        $period = app(LinkVisitPerWeekChart::class)->period();

        // Assert
        $this->assertCount($period->count(), $chartData);
        $this->assertEquals(1, $chartData[$period->count() - 1]); // this week
        $this->assertEquals(2, $chartData[$period->count() - 2]); // 1 weeks ago
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_url()
    {
        $url = Url::factory()->create();
        // This week
        Visit::factory()->for($url)->create(['created_at' => Carbon::now()->startOfWeek()]);
        Visit::factory()->create(['created_at' => Carbon::now()->startOfWeek()]);
        // 1 weeks ago
        Visit::factory()->for($url)->count(2)->create(['created_at' => Carbon::now()->subWeeks(1)->startOfWeek()]);
        Visit::factory()->create(['created_at' => Carbon::now()->subWeeks(1)->startOfWeek()]);

        $chartData = $this->chartComponent($url)->chartData();
        $period = app(LinkVisitPerWeekChart::class)->period();

        // Assert
        $this->assertCount($period->count(), $chartData); // 6 months = x weeks
        $this->assertEquals(1, $chartData[$period->count() - 1]); // this week
        $this->assertEquals(2, $chartData[$period->count() - 2]); // 1 weeks ago
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_unique_visitors_all()
    {
        $period = app(LinkVisitPerWeekChart::class)->period();
        $thisWeekStart = Carbon::now()->startOfWeek();
        $oneWeekAgoStart = Carbon::now()->subWeek()->startOfWeek();
        $twoWeeksAgoStart = Carbon::now()->subWeeks(2)->startOfWeek();

        // Arrange: Visits with duplicate UIDs within the same week
        // This week: uid1 (2x), uid2 (1x) -> Expected Unique: 2
        Visit::factory()->create(['created_at' => $thisWeekStart, 'user_uid' => 'visitor-wk-1']);
        Visit::factory()->create(['created_at' => $thisWeekStart->copy()->addDay(), 'user_uid' => 'visitor-wk-1']);
        Visit::factory()->create(['created_at' => $thisWeekStart->copy()->addDays(2), 'user_uid' => 'visitor-wk-2']);
        // One week ago: uid5 (2x) -> Expected Unique: 1
        Visit::factory()->create(['created_at' => $oneWeekAgoStart, 'user_uid' => 'visitor-wk-5']);
        Visit::factory()->create(['created_at' => $oneWeekAgoStart->copy()->addDay(), 'user_uid' => 'visitor-wk-5']);
        // Two weeks ago: uid1 (1x), uid3 (1x), uid3 (1x) -> Expected Unique: 2
        Visit::factory()->create(['created_at' => $twoWeeksAgoStart, 'user_uid' => 'visitor-wk-1']);
        Visit::factory()->guest()->create(['created_at' => $twoWeeksAgoStart->copy()->addDay(), 'user_uid' => 'visitor-wk-3']);
        Visit::factory()->guest()->create(['created_at' => $twoWeeksAgoStart->copy()->addDays(2), 'user_uid' => 'visitor-wk-3']);
        // Visit outside period (should be ignored)
        Visit::factory()->create(['created_at' => $period->getStartDate()->copy()->subWeek(), 'user_uid' => 'visitor-wk-4']);

        // Act
        $chartData = $this->chartComponent()->chartData(visitor: true);

        // Assert
        $this->assertCount($period->count(), $chartData);
        // Last index is 'this week'
        $this->assertEquals(2, $chartData[$period->count() - 1], 'Unique visitors count for this week mismatch.');
        // Second to last index is '1 week ago' (should be 0 based on setup)
        $this->assertEquals(1, $chartData[$period->count() - 2], 'Unique visitors count for 1 week ago mismatch.');
        // Third to last index is '2 weeks ago'
        $this->assertEquals(2, $chartData[$period->count() - 3], 'Unique visitors count for 2 weeks ago mismatch.');
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_unique_visitors_user()
    {
        $period = app(LinkVisitPerWeekChart::class)->period();
        $thisWeekStart = Carbon::now()->startOfWeek();
        $oneWeekAgoStart = Carbon::now()->subWeek()->startOfWeek();

        $user = User::factory()->create();
        $userUrl = Url::factory()->for($user, 'author')->create();
        $otherUrl = Url::factory()->create();

        // Arrange: Visits for the specific user's URL
        // This week: uid1 (2x), uid2 (1x) on userUrl -> Expected Unique for user: 2
        Visit::factory()->for($userUrl)->create(['created_at' => $thisWeekStart, 'user_uid' => 'user-visitor-wk-1']);
        Visit::factory()->for($userUrl)->create(['created_at' => $thisWeekStart->copy()->addDay(), 'user_uid' => 'user-visitor-wk-1']);
        Visit::factory()->for($userUrl)->create(['created_at' => $thisWeekStart->copy()->addDays(2), 'user_uid' => 'user-visitor-wk-2']);
        // One week ago: uid1 (1x) on userUrl -> Expected Unique for user: 1
        Visit::factory()->for($userUrl)->create(['created_at' => $oneWeekAgoStart, 'user_uid' => 'user-visitor-wk-1']);
        // Visits for other URL or outside period (should be ignored)
        Visit::factory()->for($otherUrl)->create(['created_at' => $thisWeekStart, 'user_uid' => 'other-visitor-wk-1']);
        Visit::factory()->guest()->create(['created_at' => $oneWeekAgoStart, 'user_uid' => 'guest-visitor-wk']);
        Visit::factory()->for($userUrl)->create(['created_at' => $period->getStartDate()->copy()->subWeek(), 'user_uid' => 'user-visitor-wk-1']);

        // Act
        $chartData = $this->chartComponent($user)->chartData(visitor: true);

        // Assert
        $this->assertCount($period->count(), $chartData);
        $this->assertEquals(2, $chartData[$period->count() - 1], 'Unique visitors count for user this week mismatch.');
        $this->assertEquals(1, $chartData[$period->count() - 2], 'Unique visitors count for user 1 week ago mismatch.');
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_unique_visitors_url()
    {
        $period = app(LinkVisitPerWeekChart::class)->period();
        $thisWeekStart = Carbon::now()->startOfWeek();
        $oneWeekAgoStart = Carbon::now()->subWeek()->startOfWeek();

        $targetUrl = Url::factory()->create();
        $otherUrl = Url::factory()->create();

        // Arrange: Visits for the specific URL
        // This week: uid1 (2x), uid2 (1x) on targetUrl -> Expected Unique for URL: 2
        Visit::factory()->for($targetUrl)->create(['created_at' => $thisWeekStart, 'user_uid' => 'url-visitor-wk-1']);
        Visit::factory()->for($targetUrl)->create(['created_at' => $thisWeekStart->copy()->addDay(), 'user_uid' => 'url-visitor-wk-1']);
        Visit::factory()->for($targetUrl)->create(['created_at' => $thisWeekStart->copy()->addDays(2), 'user_uid' => 'url-visitor-wk-2']);
        // One week ago: uid1 (1x) on targetUrl -> Expected Unique for URL: 1
        Visit::factory()->for($targetUrl)->create(['created_at' => $oneWeekAgoStart, 'user_uid' => 'url-visitor-wk-1']);
        // Visits for other URL or outside period (should be ignored)
        Visit::factory()->for($otherUrl)->create(['created_at' => $thisWeekStart, 'user_uid' => 'other-visitor-wk-1']);
        Visit::factory()->guest()->create(['created_at' => $oneWeekAgoStart, 'user_uid' => 'guest-visitor-wk']);
        Visit::factory()->for($targetUrl)->create(['created_at' => $period->getStartDate()->copy()->subWeek(), 'user_uid' => 'url-visitor-wk-1']);

        // Act
        $chartData = $this->chartComponent($targetUrl)->chartData(visitor: true);

        // Assert
        $this->assertCount($period->count(), $chartData);
        $this->assertEquals(2, $chartData[$period->count() - 1], 'Unique visitors count for URL this week mismatch.');
        $this->assertEquals(1, $chartData[$period->count() - 2], 'Unique visitors count for URL 1 week ago mismatch.');
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_label()
    {
        $chartLabel = $this->chartComponent()->chartLabel();
        $period = app(LinkVisitPerWeekChart::class)->period();

        // Assert
        $this->assertCount($period->count(), $chartLabel);
        $this->assertEquals('Oct 21 - Oct 27', $chartLabel[0]);
        $this->assertEquals('Dec 30 - Jan 05', $chartLabel[$period->count() - 1]);
    }
}
