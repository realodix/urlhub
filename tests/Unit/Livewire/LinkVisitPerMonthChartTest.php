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
    public function it_returns_correct_chart_label()
    {
        $chartLabel = $this->chartComponent()->chartLabel();

        // Assert
        $this->assertCount(13, $chartLabel); // 1 year + this month
        $this->assertEquals('Dec 2023', $chartLabel[0]);
        $this->assertEquals('Dec 2024', $chartLabel[12]);
    }
}
