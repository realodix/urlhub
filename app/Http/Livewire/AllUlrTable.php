<?php

namespace App\Http\Livewire;

use App\Models\Url;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

/**
 * @codeCoverageIgnore
 */
final class AllUlrTable extends PowerGridComponent
{
    use ActionButton;

    public bool $showUpdateMessages = true;
    public string $sortDirection = 'desc';

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): void
    {
        $this->showRecordCount('full')
            ->showPerPage()
            ->showSearchInput();
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */
    public function datasource(): ?Builder
    {
        return Url::query()
            ->join('users', function ($user) {
                $user->on('urls.user_id', '=', 'users.id');
            })
            ->select([
                'urls.id',
                'urls.keyword',
                'urls.long_url',
                'urls.meta_title',
                'urls.clicks',
                'urls.created_at',
                'urls.user_id',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    */
    public function addColumns(): ?PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('user_name', function (Url $url) {
                return '<span class="font-semibold">'.$url->user->name.'</span>';
            })
            ->addColumn('keyword', function (Url $url) {
                return
                    '<a href="'.$url->short_url.'" target="_blank" class="font-semibold">'.$url->keyword.'</a>'
                    .Blade::render('<x-gmdi-open-in-new-o class="!h-[0.7em] ml-1"/>');
            })
            ->addColumn('long_url', function (Url $url) {
                return
                    '<span title="'.$url->meta_title.'" class="font-semibold">'
                        .Str::limit($url->meta_title, 80).
                    '</span>
                    <br>
                    <a href="'.$url->long_url.'" target="_blank" title="'.$url->long_url.'" rel="noopener noreferrer" class="text-slate-500">'
                        .urlDisplay($url->long_url, false, 70)
                        .Blade::render('<x-gmdi-open-in-new-o class="!h-[0.7em] ml-1"/>').
                    '</a>';
            })
            ->addColumn('clicks', fn (Url $url) => $url->clicks.Blade::render('<x-gmdi-bar-chart class="ml-2"/>'))
            ->addColumn('created_at_formatted', function (Url $url) {
                return
                    '<span title="'.$url->created_at->toDayDateTimeString().'">'
                        .$url->created_at->diffForHumans().
                    '</span>';
            })
            ->addColumn('action', function (Url $url) {
                return
                    '<a role="button" href="'.route('short_url.stats', $url->keyword).'" target="_blank" title="'.__('Open front page').'" class="btn-card btn-action">'
                        .Blade::render('<x-carbon-data-view-alt />').
                    '</a>
                    <a role="button" href="'.route('dashboard.duplicate', $url->keyword).'" title="'.__('Duplicate').'" class="btn-card btn-action">'
                        .Blade::render('<x-far-clone />').
                    '</a>
                    <a role="button" href="'.route('short_url.edit', $url->keyword).'" title="'.__('Edit').'" class="btn-card btn-action">'
                        .Blade::render('<x-far-edit />').
                    '</a>
                    <a role="button" href="'.route('dashboard.delete', $url->getRouteKey()).'" title="'.__('Delete').'" class="btn-card btn-action-delete">'
                        .Blade::render('<x-far-trash-alt />').
                    '</a>';
            });
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::add()
                ->title('Owner')
                ->field('user_name', 'users.name')
                ->sortable()
                ->searchable(),

            Column::add()
                ->title('Short URL')
                ->field('keyword')
                ->sortable()
                ->searchable(),

            Column::add()
                ->title('Destination URL')
                ->field('long_url')
                ->sortable()
                ->searchable(),

            Column::add()
                ->title('CLICKS')
                ->field('clicks'),

            Column::add()
                ->title('CREATED AT')
                ->field('created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),

            Column::add()
                ->title('ACTIONS')
                ->field('action'),

        ];
    }
}
