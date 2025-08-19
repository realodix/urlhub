<?php

namespace App\Livewire\Table;

use App\Models\Url;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

/**
 * @codeCoverageIgnore
 */
abstract class BaseUrlTable extends PowerGridComponent
{
    /** @var int */
    const STR_LIMIT = 90;

    /** @var int */
    const PER_PAGE = 25;

    /** @var list<int> */
    const PER_PAGE_VALUES = [10, 25, 50, 100];

    public string $sortDirection = 'desc';

    /**
     * @param Builder<Url> $query
     * @return Builder<Url>
     */
    abstract protected function scopeDatasource(Builder $query): Builder;

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(self::PER_PAGE, self::PER_PAGE_VALUES)
                ->showRecordCount('full'),
        ];
    }

    /**
     * @return Builder<Url>
     */
    public function datasource(): Builder
    {
        return Url::where(fn(Builder $query) => $this->scopeDatasource($query))
            ->with('author')
            ->withCount([
                'visits',
                'visits as unique_visit_count' => function (Builder $query) {
                    /** @var Builder<\App\Models\Visit> $query */
                    $query->where('is_first_click', true);
                },
            ]);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('keyword', function (Url $url) {
                return view('components.table.keyword', ['model' => $url])->render();
            })
            ->add('destination', function (Url $url) {
                return view('components.table.destination', [
                    'model' => $url,
                    'title' => $url->title,
                    'destination' => $url->destination,
                    'limit' => static::STR_LIMIT,
                ])->render();
            })
            ->add('visits_count', function (Url $url) {
                return view('components.table.visit', [
                    'clicks' => $url->visits_count,
                    'uniqueClicks' => $url->unique_visit_count,
                ])->render();
            })
            ->add('created_at_formatted', function (Url $url) {
                return view('components.table.date-created', [
                    'date' => $url->created_at->inUserTz(),
                ])->render();
            })
            ->add('action', function (Url $url) {
                return view('components.table.action-button', [
                    'detail_link' => route('link_detail', $url->keyword),
                    'delete_link' => route('link.delete', $url),
                ])->render();
            });
    }

    /**
     * @return array<Column>
     */
    public function columns(): array
    {
        return [
            Column::make('Short URL', 'keyword')
                ->sortable()
                ->searchable(),
            Column::make('Destination URL', 'destination')
                ->sortable()
                ->searchable(),
            Column::make('Clicks', 'visits_count')
                ->sortable(),
            Column::make('CREATED AT', 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),
            Column::make('ACTIONS', 'action'),
        ];
    }
}
