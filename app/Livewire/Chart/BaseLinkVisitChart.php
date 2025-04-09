<?php

namespace App\Livewire\Chart;

use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

/**
 * @codeCoverageIgnore
 */
abstract class BaseLinkVisitChart extends ChartWidget
{
    protected static ?string $maxHeight = '250px';

    public User|Url|null $model = null;

    /** {@inheritdoc} */
    protected function getType(): string
    {
        return 'line';
    }

    /** {@inheritdoc} */
    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label'           => 'Visits',
                    'data'            => $this->chartData(),
                    'backgroundColor' => '#006edb',
                    'borderColor'     => '#006edb',
                ],
            ],
            'labels' => $this->chartLabel(),
        ];
    }

    abstract public function chartData(): array;

    abstract public function chartLabel(): array;

    /**
     * Return the visits data for the given period.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Visit>
     */
    protected function getDataForPeriod(CarbonPeriod $period)
    {
        return Visit::query()
            ->when($this->model instanceof User, function ($query) {
                $query->whereRelation('url', 'user_id', $this->model->id);
            })
            ->when($this->model instanceof Url, function ($query) {
                $query->where('url_id', $this->model->id);
            })
            ->whereBetween('created_at', [$period->getStartDate(), $period->getEndDate()])
            ->get();
    }
}
