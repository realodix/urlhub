<?php

namespace App\Livewire\Chart;

use App\Models\Visit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;

class LinkVisitPerMonthChart extends BaseLinkVisitChart
{
    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     */
    public function getDescription(): ?string
    {
        return 'Stats for the past one year';
    }

    /**
     * Get the period for the chart.
     *
     * @return \Carbon\CarbonPeriod
     */
    public function period()
    {
        $startDate = Carbon::now()->subYear()->startOfMonth(); // Monday
        $endDate = Carbon::now()->endOfMonth(); // Sunday

        return CarbonPeriod::create($startDate, '1 month', $endDate);
    }

    public function chartData(bool $visitor = false): array
    {
        $period = $this->period();
        $visits = $this->getPeriodData($period);

        $groupByFormat = fn(Visit $visit) => $visit->created_at->startOfMonth()->format('Y-m');
        if ($visitor) {
            // Group by month, then calculate the unique `user_uid` per month
            $rawData = $visits->groupBy($groupByFormat)
                ->map(function (Collection $monthlyVisits) {
                    return $monthlyVisits->pluck('user_uid')->unique()->count();
                });
        } else {
            $rawData = $visits->countBy($groupByFormat);
        }

        // Calculate the number of visits per month
        $data = [];
        foreach ($period as $month) {
            $startOfMonth = $month->copy()->startOfMonth()->format('Y-m');
            $data[] = $rawData->get($startOfMonth, 0);
        }

        return $data;
    }

    public function chartLabel(): array
    {
        return [...$this->period()->map(fn($month) => $month->format('M Y'))];
    }
}
