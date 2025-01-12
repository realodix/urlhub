<?php

namespace App\Livewire\Chart;

use App\Models\Url;
use App\Models\Visit;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class LinkVisitChart extends ChartWidget
{
    protected static ?string $maxHeight = '250px';

    public Url $model;

    protected function getData(): array
    {
        $startDate = now()->subQuarter();
        $endDate = now();
        $carbon = CarbonPeriod::create($startDate, $endDate)->toArray();
        $label = collect($carbon)->map(fn($date) => $date->format('M d'))
            ->toArray();

        $visitModel = Visit::where('url_id', $this->model->id);
        $data = Trend::query($visitModel)
            ->between(start: $startDate, end: $endDate)
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Visits',
                    'data'  => $data->map(fn(TrendValue $value) => $value->aggregate),
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
