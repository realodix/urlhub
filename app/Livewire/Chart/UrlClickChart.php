<?php

namespace App\Livewire\Chart;

use App\Models\Url;
use App\Models\Visit;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UrlClickChart extends ChartWidget
{
    protected static ?string $maxHeight = '250px';

    public Url $model;

    protected function getData(): array
    {
        $startDate = now()->subQuarter();
        $endDate = now();
        $carbon = CarbonPeriod::create($startDate, $endDate)->toArray();
        $label = collect($carbon)->map(fn ($date) => $date->format('M d'))
            ->toArray();

        $visitModel = Visit::where('url_id', $this->model->id);
        $data = Trend::query($visitModel)
            ->between(start: $startDate, end: $endDate)
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Clicks',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
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
        return 'Number of clicks on each day for 4 months';
    }
}
