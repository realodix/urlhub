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
class BaseUrlTable extends PowerGridComponent
{
    /** @var int */
    const STR_LIMIT = 90;

    public string $sortDirection = 'desc';

    public string $sortField = 'urls.id';

    public string $primaryKey = 'urls.id';

    public function getUserIdBuilder(Builder $query): Builder
    {
        return $query;
    }

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(25, [10, 25, 50, 100])
                ->showRecordCount('full'),
        ];
    }

    public function datasource(): Builder
    {
        return Url::where(fn(Builder $query) => $this->getUserIdBuilder($query))
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
            ->add('author', function (Url $url) {
                $author = $url->author->name;

                return view('components.table.author', ['name' => $author])
                    ->render();
            })
            ->add('keyword', function (Url $url) {
                return view('components.table.keyword', [
                    'shortLink' => $url->short_url,
                    'keyword'   => $url->keyword,
                ])->render();
            })
            ->add('destination', function (Url $url) {
                return view('components.table.destination', [
                    'title' => $url->title,
                    'destination' => $url->destination,
                    'limit' => static::STR_LIMIT,
                ])->render();
            })
            ->add('t_clicks', function (Url $url) {
                return view('components.table.visit', [
                    'clicks'       => $url->visits_count,
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
                    'edit_link'   => route('link.edit', $url),
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
            Column::make('title', 'title')
                ->searchable()
                ->hidden(),

            Column::make('CLICKS', 't_clicks'),

            Column::make('CREATED AT', 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),

            Column::make('ACTIONS', 'action'),
        ];
    }
}
