<?php

namespace App\Livewire\Chart;

use App\Models\Visit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;

class LinkVisitPerWeekChart extends BaseLinkVisitChart
{
    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     */
    public function getDescription(): ?string
    {
        return 'Stats for the past 10 weeks';
    }

    /**
     * Get the period for the chart.
     *
     * @return \Carbon\CarbonPeriod
     */
    public function period()
    {
        $startDate = Carbon::now()->subWeeks(10)->startOfWeek(); // Monday
        $endDate = Carbon::now()->endOfWeek(); // Sunday

        return CarbonPeriod::create($startDate, '1 week', $endDate);
    }

    public function chartData(bool $visitor = false): array
    {
        $period = $this->period();
        $visits = $this->getPeriodData($period);

        $groupByFormat = fn(Visit $visit) => $visit->created_at->startOfWeek()->format('Y-m-d');
        if ($visitor) {
            // Group by week, then calculate unique `user_uid` per week
            $rawData = $visits->groupBy($groupByFormat)
                ->map(function (Collection $weeklyVisits) {
                    return $weeklyVisits->pluck('user_uid')->unique()->count();
                });
        } else {
            $rawData = $visits->countBy($groupByFormat);
        }

        // Calculate the number of visits per week
        $data = [];
        foreach ($period as $week) {
            $startOfWeek = $week->copy()->startOfWeek()->format('Y-m-d');
            $data[] = $rawData->get($startOfWeek, 0);
        }

        return $data;
    }

    public function chartLabel(): array
    {
        $label = [];
        foreach ($this->period() as $week) {
            $startOfWeek = $week->copy()->startOfWeek(); // Monday
            $endOfWeek = $week->copy()->endOfWeek(); // Sunday
            $label[] = $startOfWeek->format('M d').' - '.$endOfWeek->format('M d');
        }

        return $label;
    }
}
