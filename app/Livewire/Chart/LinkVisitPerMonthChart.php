<?php

namespace App\Livewire\Chart;

use App\Models\Visit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LinkVisitPerMonthChart extends BaseLinkVisitChart
{
    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     */
    public function getDescription(): ?string
    {
        return 'Stats for past one year (month by month)';
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

    public function chartData(): array
    {
        $period = $this->period();

        $rawData = $this->getDataForPeriod($period)
            ->countBy(function (Visit $visit) {
                return $visit->created_at->startOfMonth()->format('Y-m');
            });

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
