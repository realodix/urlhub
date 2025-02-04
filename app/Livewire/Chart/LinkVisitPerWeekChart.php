<?php

namespace App\Livewire\Chart;

use App\Models\Url;
use App\Models\Visit;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

class LinkVisitPerWeekChart extends ChartWidget
{
    protected static ?string $maxHeight = '250px';

    public Url $model;

    protected function getData(): array
    {
        $startDate = now()->subQuarter()->startOfWeek(); // Monday
        $endDate = now()->endOfWeek(); // Sunday

        // Create a weekly range
        $weeks = CarbonPeriod::create($startDate, '1 week', $endDate);

        // Label format per week (Jan 01 - Jan 07)
        $label = [];
        foreach ($weeks as $week) {
            $startOfWeek = $week->copy()->startOfWeek(); // Monday
            $endOfWeek = $week->copy()->endOfWeek(); // Sunday
            $label[] = $startOfWeek->format('M d') . ' - ' . $endOfWeek->format('M d');
        }

        // Retrieve all data in the time range
        $visitModel = Visit::where('url_id', $this->model->id);
        $rawData = $visitModel->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            // Group by start of week
            ->groupBy(fn(Visit $visit) => $visit->created_at->startOfWeek()->format('Y-m-d'));

        // Calculate the number of visits per week
        $data = [];
        foreach ($weeks as $week) {
            $startOfWeek = $week->copy()->startOfWeek()->format('Y-m-d');
            $data[] = $rawData->get($startOfWeek, collect())->count();
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Visits',
                    'data'            => $data,
                    'backgroundColor' => '#006edb',
                    'borderColor'     => '#006edb',
                ],
            ],
            'labels' => $label,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getDescription(): ?string
    {
        return 'Stats for past quarter';
    }
}
