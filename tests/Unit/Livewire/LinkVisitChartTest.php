<?php

namespace Tests\Unit\Livewire;

use App\Livewire\Chart\LinkVisitChart;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

class LinkVisitChartTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Freeze time to make the 'last quarter' predictable
        // Let's set 'now' to a specific date
        $this->travelTo('2024-12-31');
    }

    protected function tearDown(): void
    {
        $this->travelBack(); // Unfreeze time
        parent::tearDown();
    }

    private function chartComponent($model = null)
    {
        return Livewire::test(LinkVisitChart::class, ['model' => $model])
            ->instance();
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_all_visits()
    {
        $period = app(LinkVisitChart::class)->period();
        $startDate = $period->getStartDate();
        $endDate = $period->getEndDate(); // This is 'now' (2024-12-31)
        $yesterday = $endDate->copy()->subDay();

        // Visits within the period
        Visit::factory()->create(['created_at' => $endDate]); // Today
        Visit::factory()->count(2)->create(['created_at' => $yesterday]); // Yesterday
        Visit::factory()->guest()->create(['created_at' => $startDate]); // Start of quarter
        Visit::factory()->create(['created_at' => $startDate->copy()->addDays(10)]); // Somewhere in the middle
        // Visit outside the period (should be ignored)
        Visit::factory()->create(['created_at' => $startDate->copy()->subDay()]);

        $chartData = $this->chartComponent()->chartData();

        // Assert
        $this->assertCount($period->count(), $chartData);
        // Check specific days based on our seeded data
        // Index 0 corresponds to the start date
        $this->assertEquals(1, $chartData[0], 'Count at start date mismatch.');
        // Index $period->count() - 1 corresponds to the end date (today)
        $this->assertEquals(1, $chartData[$period->count() - 1], 'Count at end date (today) mismatch.');
        // Index $period->count() - 2 corresponds to yesterday
        $this->assertEquals(2, $chartData[$period->count() - 2], 'Count at yesterday mismatch.');
        // Check the day we added 'somewhere in the middle' (10 days after start)
        $this->assertEquals(1, $chartData[10], 'Count at start date + 10 days mismatch.');

        // Check total sum matches the number of visits created within the period
        $this->assertEquals(1 + 2 + 1 + 1, array_sum($chartData));
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_a_specific_user()
    {
        $period = app(LinkVisitChart::class)->period();
        $startDate = $period->getStartDate();
        $endDate = $period->getEndDate();
        $yesterday = $endDate->copy()->subDay();

        $user = User::factory()->create();
        $userUrl = Url::factory()->for($user, 'author')->create();
        $otherUrl = Url::factory()->create(); // Belongs to another user or guest

        // Visits for the specific user's URL within the period
        Visit::factory()->for($userUrl)->create(['created_at' => $endDate]); // Today
        Visit::factory()->for($userUrl)->count(2)->create(['created_at' => $yesterday]); // Yesterday
        Visit::factory()->for($userUrl)->create(['created_at' => $startDate]); // Start of quarter
        // Visits for another URL (should be ignored)
        Visit::factory()->for($otherUrl)->create(['created_at' => $endDate]);
        Visit::factory()->guest()->create(['created_at' => $yesterday]);
        // Visit for the user's URL outside the period (should be ignored)
        Visit::factory()->for($userUrl)->create(['created_at' => $startDate->copy()->subDay()]);

        $chartData = $this->chartComponent($user)->chartData();

        // Assert
        $this->assertCount($period->count(), $chartData);
        // Check specific days
        $this->assertEquals(1, $chartData[0], 'Count at start date mismatch.'); // Start date
        $this->assertEquals(1, $chartData[$period->count() - 1], 'Count at end date (today) mismatch.'); // Today
        $this->assertEquals(2, $chartData[$period->count() - 2], 'Count at yesterday mismatch.'); // Yesterday

        // Check total sum matches the number of visits for the user's URLs within the period
        $this->assertEquals(1 + 2 + 1, array_sum($chartData));
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_a_specific_url()
    {
        $period = app(LinkVisitChart::class)->period();
        $startDate = $period->getStartDate();
        $endDate = $period->getEndDate();
        $yesterday = $endDate->copy()->subDay();

        $targetUrl = Url::factory()->create();
        $otherUrl = Url::factory()->create();

        // Visits for the specific URL within the period
        Visit::factory()->for($targetUrl)->create(['created_at' => $endDate]); // Today
        Visit::factory()->for($targetUrl)->count(2)->create(['created_at' => $yesterday]); // Yesterday
        Visit::factory()->for($targetUrl)->create(['created_at' => $startDate]); // Start of quarter
        // Visits for another URL (should be ignored)
        Visit::factory()->for($otherUrl)->create(['created_at' => $endDate]);
        Visit::factory()->guest()->create(['created_at' => $yesterday]);
        // Visit for the target URL outside the period (should be ignored)
        Visit::factory()->for($targetUrl)->create(['created_at' => $startDate->copy()->subDay()]);

        $chartData = $this->chartComponent($targetUrl)->chartData();

        // Assert
        $this->assertCount($period->count(), $chartData);
        // Check specific days
        $this->assertEquals(1, $chartData[0], 'Count at start date mismatch.'); // Start date
        $this->assertEquals(1, $chartData[$period->count() - 1], 'Count at end date (today) mismatch.'); // Today
        $this->assertEquals(2, $chartData[$period->count() - 2], 'Count at yesterday mismatch.'); // Yesterday

        // Check total sum matches the number of visits for the target URL within the period
        $this->assertEquals(1 + 2 + 1, array_sum($chartData));
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_unique_visitors_all()
    {
        $period = app(LinkVisitChart::class)->period();
        $endDate = $period->getEndDate(); // 2024-12-31
        $yesterday = $endDate->copy()->subDay(); // 2024-12-30
        $twoDaysAgo = $endDate->copy()->subDays(2); // 2024-12-29

        // Arrange: Visits with duplicate UIDs within the same day
        // Today (endDate): uid1 (2x), uid2 (1x) -> Expected Unique: 2
        Visit::factory()->create(['created_at' => $endDate, 'user_uid' => 'visitor-1']);
        Visit::factory()->create(['created_at' => $endDate, 'user_uid' => 'visitor-1']);
        Visit::factory()->create(['created_at' => $endDate, 'user_uid' => 'visitor-2']);
        // Yesterday: uid1 (1x), uid3 (1x) -> Expected Unique: 2
        Visit::factory()->create(['created_at' => $yesterday, 'user_uid' => 'visitor-1']);
        Visit::factory()->guest()->create(['created_at' => $yesterday, 'user_uid' => 'visitor-3']); // Guest visitor
        // Two days ago: uid2 (1x) -> Expected Unique: 1
        Visit::factory()->create(['created_at' => $twoDaysAgo, 'user_uid' => 'visitor-2']);
        // Visit outside period (should be ignored)
        Visit::factory()->create(['created_at' => $period->getStartDate()->copy()->subDay(), 'user_uid' => 'visitor-4']);

        // Act
        $chartData = $this->chartComponent()->chartData(visitor: true);

        // Assert
        $this->assertCount($period->count(), $chartData);
        // Index $period->count() - 1 corresponds to the end date (today)
        $this->assertEquals(2, $chartData[$period->count() - 1], 'Unique visitors count for today mismatch.');
        // Index $period->count() - 2 corresponds to yesterday
        $this->assertEquals(2, $chartData[$period->count() - 2], 'Unique visitors count for yesterday mismatch.');
        // Index $period->count() - 3 corresponds to two days ago
        $this->assertEquals(1, $chartData[$period->count() - 3], 'Unique visitors count for two days ago mismatch.');

        // Check a day with no visits
        if ($period->count() > 4) {
            $this->assertEquals(0, $chartData[$period->count() - 4], 'Unique visitors count for three days ago should be 0.');
        }

        // Check total sum matches the number of unique visitors within the period
        $this->assertEquals(2 + 2 + 1, array_sum($chartData));
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_unique_visitors_user()
    {
        $period = app(LinkVisitChart::class)->period();
        $endDate = $period->getEndDate();
        $yesterday = $endDate->copy()->subDay();

        $user = User::factory()->create();
        $userUrl = Url::factory()->for($user, 'author')->create();
        $otherUrl = Url::factory()->create();

        // Arrange: Visits for the specific user's URL
        // Today: uid1 (2x), uid2 (1x) on userUrl -> Expected Unique for user: 2
        Visit::factory()->for($userUrl)->create(['created_at' => $endDate, 'user_uid' => 'user-visitor-1']);
        Visit::factory()->for($userUrl)->create(['created_at' => $endDate, 'user_uid' => 'user-visitor-1']);
        Visit::factory()->for($userUrl)->create(['created_at' => $endDate, 'user_uid' => 'user-visitor-2']);
        // Yesterday: uid1 (1x) on userUrl -> Expected Unique for user: 1
        Visit::factory()->for($userUrl)->create(['created_at' => $yesterday, 'user_uid' => 'user-visitor-1']);
        // Visits for other URL or outside period (should be ignored by the component's filter)
        Visit::factory()->for($otherUrl)->create(['created_at' => $endDate, 'user_uid' => 'other-visitor-1']);
        Visit::factory()->guest()->create(['created_at' => $yesterday, 'user_uid' => 'guest-visitor']);
        Visit::factory()->for($userUrl)->create(['created_at' => $period->getStartDate()->copy()->subDay(), 'user_uid' => 'user-visitor-1']);

        // Act
        $chartData = $this->chartComponent($user)->chartData(visitor: true);

        // Assert
        $this->assertCount($period->count(), $chartData);
        $this->assertEquals(2, $chartData[$period->count() - 1], 'Unique visitors count for user today mismatch.');
        $this->assertEquals(1, $chartData[$period->count() - 2], 'Unique visitors count for user yesterday mismatch.');
        // Other days should be 0 unless visits were added there
        $this->assertEquals(0, $chartData[0], 'Unique visitors count for user at start date should be 0.');
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_data_for_unique_visitors_url()
    {
        $period = app(LinkVisitChart::class)->period();
        $endDate = $period->getEndDate();
        $yesterday = $endDate->copy()->subDay();

        $targetUrl = Url::factory()->create();
        $otherUrl = Url::factory()->create();

        // Arrange: Visits for the specific URL
        // Today: uid1 (2x), uid2 (1x) on targetUrl -> Expected Unique for URL: 2
        Visit::factory()->for($targetUrl)->create(['created_at' => $endDate, 'user_uid' => 'url-visitor-1']);
        Visit::factory()->for($targetUrl)->create(['created_at' => $endDate, 'user_uid' => 'url-visitor-1']);
        Visit::factory()->for($targetUrl)->create(['created_at' => $endDate, 'user_uid' => 'url-visitor-2']);
        // Yesterday: uid1 (1x) on targetUrl -> Expected Unique for URL: 1
        Visit::factory()->for($targetUrl)->create(['created_at' => $yesterday, 'user_uid' => 'url-visitor-1']);
        // Visits for other URL or outside period (should be ignored)
        Visit::factory()->for($otherUrl)->create(['created_at' => $endDate, 'user_uid' => 'other-visitor-1']);
        Visit::factory()->guest()->create(['created_at' => $yesterday, 'user_uid' => 'guest-visitor']);
        Visit::factory()->for($targetUrl)->create(['created_at' => $period->getStartDate()->copy()->subDay(), 'user_uid' => 'url-visitor-1']);

        // Act
        $chartData = $this->chartComponent($targetUrl)->chartData(visitor: true);

        // Assert
        $this->assertCount($period->count(), $chartData);
        $this->assertEquals(2, $chartData[$period->count() - 1], 'Unique visitors count for URL today mismatch.');
        $this->assertEquals(1, $chartData[$period->count() - 2], 'Unique visitors count for URL yesterday mismatch.');
        $this->assertEquals(0, $chartData[0], 'Unique visitors count for URL at start date should be 0.');
    }

    #[PHPUnit\Test]
    public function it_returns_correct_chart_label()
    {
        $period = app(LinkVisitChart::class)->period();
        $chartLabel = $this->chartComponent()->chartLabel();

        // Assert
        // 1. Check the count matches the number of days in the period
        $this->assertCount($period->count(), $chartLabel, 'The number of labels should match the number of days in the period.');
        // 2. Explicitly check the first and last labels based on frozen time
        // Start date = Oct 26 - 1 quarter = Jul 26
        $this->assertEquals(
            'Nov 19',
            $chartLabel[0],
            'First label does not match the formatted start date.',
        );
        // End date = Oct 26
        $this->assertEquals(
            'Dec 31',
            $chartLabel[$period->count() - 1],
            'Last label does not match the formatted end date.',
        );
    }
}
