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
    public function it_returns_correct_chart_label()
    {
        $chartLabel = $this->chartComponent()->chartLabel();
        $period = app(LinkVisitPerWeekChart::class)->period();

        // Assert
        $this->assertCount($period->count(), $chartLabel);
        $this->assertEquals('Jul 01 - Jul 07', $chartLabel[0]);
        $this->assertEquals('Dec 30 - Jan 05', $chartLabel[$period->count() - 1]);
    }
}
