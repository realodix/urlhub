<?php

namespace App\Livewire\Chart;

use App\Models\Url;
use App\Models\Visit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

/**
 * @codeCoverageIgnore
 */
class LinkVisitPerMonthChart extends ChartWidget
{
    protected static ?string $maxHeight = '250px';

    public Url $model;

    public function getDescription(): ?string
    {
        return 'Stats for past one year (month by month)';
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $startDate = Carbon::now()->subYear()->startOfMonth(); // Monday
        $endDate = Carbon::now()->endOfMonth(); // Sunday
        $period = CarbonPeriod::create($startDate, '1 month', $endDate);

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

    protected function chartData(Carbon $startDate, Carbon $endDate, CarbonPeriod $period): array
    {
        $model = Visit::where('url_id', $this->model->id);
        $rawData = $model->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->countBy(fn(Visit $visit) => $visit->created_at->startOfMonth()->format('Y-m')); // Group by month

        // Calculate the number of visits per month
        $data = [];
        foreach ($period as $month) {
            $startOfMonth = $month->copy()->startOfMonth()->format('Y-m');
            $data[] = $rawData->get($startOfMonth, 0);
        }

        return $data;
    }

    public function chartLabel(CarbonPeriod $period): array
    {
        return [...$period->map(fn($month) => $month->format('M Y'))];
    }
}
