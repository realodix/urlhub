<?php

namespace App\Livewire\Chart;

use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use Carbon\CarbonPeriod;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Collection;

/**
 * @codeCoverageIgnore
 */
abstract class BaseLinkVisitChart extends ChartWidget
{
    protected static ?string $maxHeight = '250px';

    protected static ?string $pollingInterval = null;

    public User|Url|null $model = null;

    /**
     * @var Collection<int,\App\Models\Visit>|null
     */
    private ?Collection $visitsData = null;

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
                    'label' => 'Visits',
                    'data' => $this->chartData(),
                    'backgroundColor' => 'rgba('.Color::Blue[400].', 0.5)',
                    'borderColor' => 'rgb('.Color::Blue[400].')',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Visitors',
                    'data' => $this->chartData(visitor: true),
                    'backgroundColor' => 'rgba('.Color::Emerald[400].', 0.7)',
                    'borderColor' => 'rgb('.Color::Emerald[400].')',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $this->chartLabel(),
        ];
    }

    abstract public function chartData(bool $visitor = false): array;

    abstract public function chartLabel(): array;

    /**
     * Return the visits data for the given period.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int,\App\Models\Visit>
     */
    protected function getPeriodData(CarbonPeriod $period)
    {
        // The `getData()` calls this function via `chartData()` twice per render:
        // "total visits" and "unique visitors". We cache the query result in
        // `$this->visitsData` on the first run to ensure the database is only
        // hit once per request, preventing duplicate queries.
        if ($this->visitsData === null) {
            $this->visitsData = Visit::query()
                ->when($this->model instanceof User, function ($query) {
                    $query->whereRelation('url', 'user_id', $this->model->id);
                })
                ->when($this->model instanceof Url, function ($query) {
                    $query->where('url_id', $this->model->id);
                })
                ->whereBetween('created_at', [$period->getStartDate(), $period->getEndDate()])
                ->get(['user_uid', 'created_at']);
        }

        return $this->visitsData;
    }
}
