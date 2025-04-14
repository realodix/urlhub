<?php

namespace App\Livewire\Chart;

use App\Models\Visit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LinkVisitPerWeekChart extends BaseLinkVisitChart
{
    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     */
    public function getDescription(): ?string
    {
        return 'Stats for past six months (week by week)';
    }

    /**
     * Get the period for the chart.
     *
     * @return \Carbon\CarbonPeriod
     */
    public function period()
    {
        $startDate = Carbon::now()->subMonths(6)->startOfWeek(); // Monday
        $endDate = Carbon::now()->endOfWeek(); // Sunday

        return CarbonPeriod::create($startDate, '1 week', $endDate);
    }

    public function chartData(): array
    {
        $period = $this->period();

        $rawData = $this->getPeriodData($period)
            ->countBy(function (Visit $visit) {
                return $visit->created_at->startOfWeek()->format('Y-m-d');
            });

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
