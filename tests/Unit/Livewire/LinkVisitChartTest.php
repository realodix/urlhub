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
        Carbon::setTestNow(); // Unfreeze time
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
            'Oct 01',
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
