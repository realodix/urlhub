<?php

namespace App\Livewire\Chart;

use App\Models\Visit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LinkVisitChart extends BaseLinkVisitChart
{
    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     */
    public function getDescription(): ?string
    {
        return 'Stats for past quarter';
    }

    /**
     * Get the period for the chart.
     *
     * @return \Carbon\CarbonPeriod
     */
    public function period()
    {
        $startDate = Carbon::now()->subQuarter();
        $endDate = Carbon::now();

        return CarbonPeriod::create($startDate, '1 day', $endDate);
    }

    /**
     * Returns the visits trend data for the given date range.
     */
    public function chartData(): array
    {
        $period = $this->period();

        $rawData = $this->getDataForPeriod($period)
            ->countBy(fn(Visit $visit) => $visit->created_at->format('Y-m-d'));

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
