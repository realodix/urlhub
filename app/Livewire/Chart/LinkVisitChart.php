<?php

namespace App\Livewire\Chart;

use App\Models\Visit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;

class LinkVisitChart extends BaseLinkVisitChart
{
    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     */
    public function getDescription(): ?string
    {
        return 'Stats for the past 6 weeks';
    }

    /**
     * Get the period for the chart.
     *
     * @return \Carbon\CarbonPeriod
     */
    public function period()
    {
        $startDate = Carbon::now()->subWeeks(6);
        $endDate = Carbon::now();

        return CarbonPeriod::create($startDate, '1 day', $endDate);
    }

    /**
     * Returns the visits trend data for the given date range.
     */
    public function chartData(bool $visitor = false): array
    {
        $period = $this->period();
        $visits = $this->getPeriodData($period);

        $groupByFormat = fn(Visit $visit) => $visit->created_at->format('Y-m-d');
        if ($visitor) {
            // Group by day, then calculate unique `user_uid` per day
            $rawData = $visits->groupBy($groupByFormat)
                ->map(function (Collection $dailyVisits) {
                    return $dailyVisits->pluck('user_uid')->unique()->count();
                });
        } else {
            $rawData = $visits->countBy($groupByFormat);
        }

        // Calculate the number of visits per day
        $data = [];
        foreach ($period as $day) {
            $currentDay = $day->format('Y-m-d');
            $data[] = $rawData->get($currentDay, 0);
        }

        return $data;
    }

    public function chartLabel(): array
    {
        return [...$this->period()->map(fn($date) => $date->format('M d'))];
    }
}
