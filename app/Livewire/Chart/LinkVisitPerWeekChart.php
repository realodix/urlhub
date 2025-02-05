<?php

namespace App\Livewire\Chart;

use App\Models\Url;
use App\Models\Visit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

class LinkVisitPerWeekChart extends ChartWidget
{
    protected static ?string $maxHeight = '250px';

    public Url $model;

    public function getDescription(): ?string
    {
        return 'Stats for past six months (week by week)';
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $startDate = Carbon::now()->subMonths(6)->startOfWeek(); // Monday
        $endDate = Carbon::now()->endOfWeek(); // Sunday
        $period = CarbonPeriod::create($startDate, '1 week', $endDate);

        return [
            'datasets' => [
                [
                    'label'           => 'Visits',
                    'data'            => $this->chartData($startDate, $endDate, $period),
                    'backgroundColor' => '#006edb',
                    'borderColor'     => '#006edb',
                ],
            ],
            'labels' => $this->chartLabel($period),
        ];
    }

    /**
     * Return the chart data
     */
    protected function chartData(Carbon $startDate, Carbon $endDate, CarbonPeriod $period): array
    {
        $model = Visit::where('url_id', $this->model->id);
        $rawData = $model->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->countBy(fn(Visit $visit) => $visit->created_at->startOfWeek()->format('Y-m-d'));

        // Calculate the number of visits per week
        $data = [];
        foreach ($period as $week) {
            $startOfWeek = $week->copy()->startOfWeek()->format('Y-m-d');
            $data[] = $rawData->get($startOfWeek, 0);
        }

        return $data;
    }

    /**
     * Label format per week (Jan 01 - Jan 07)
     */
    public function chartLabel(CarbonPeriod $period): array
    {
        $label = [];
        foreach ($period as $week) {
            $startOfWeek = $week->copy()->startOfWeek(); // Monday
            $endOfWeek = $week->copy()->endOfWeek(); // Sunday
            $label[] = $startOfWeek->format('M d') . ' - ' . $endOfWeek->format('M d');
        }

        return $label;
    }
}
