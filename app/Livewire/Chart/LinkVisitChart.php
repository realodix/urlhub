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

    public function getDescription(): ?string
    {
        return 'Stats for past quarter';
    }

    protected function getData(): array
    {
        $startDate = now()->subQuarter();
        $endDate = now();

        $visitModel = Visit::where('url_id', $this->model->id);
        $data = Trend::query($visitModel)
            ->between(start: $startDate, end: $endDate)
            ->perDay()
            ->count();

        $label = collect(CarbonPeriod::create($startDate, '1 day', $endDate))
            ->transform(fn($date) => $date->format('M d'))
            ->toArray();

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
}
