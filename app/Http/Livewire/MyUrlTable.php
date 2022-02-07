<?php

namespace App\Http\Livewire;

use App\Models\Url;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

/**
 * @codeCoverageIgnore
 */
final class MyUrlTable extends PowerGridComponent
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
        return Url::whereUserId(Auth::id());
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
            ->addColumn('keyword', function (Url $url) {
                return '<a href="'.$url->short_url.'" target="_blank" class="">'.$url->keyword.'</a>';
            })
            ->addColumn('long_url', function (Url $url) {
                return '
                    <span title="'.$url->meta_title.'">
                        '.Str::limit($url->meta_title, 80).'
                    </span>
                    <br>
                    <a href="'.$url->long_url.'" target="_blank" title="'.$url->long_url.'" class="text-gray-500">
                        '.urlDisplay($url->long_url, false, 70).'
                    </a>';
            })
            ->addColumn('clicks')
            ->addColumn('created_at_formatted', function (Url $url) {
                return
                    '<span title="'.$url->created_at->toDayDateTimeString().'">
                        '.$url->created_at->diffForHumans().
                    '</span>';
            })
            ->addColumn('action', function (Url $url) {
                return
                    '<a role="button" class="text-slate-400 hover:text-indigo-600 active:text-indigo-700" href="'.route('short_url.stats', $url->keyword).'" target="_blank" title="'.__('Details').'"><i class="fa fa-eye"></i></a>
                    <a role="button" class="text-slate-400 hover:text-indigo-600 active:text-indigo-700" href="'.route('dashboard.duplicate', $url->keyword).'" title="'.__('Duplicate').'"><i class="far fa-clone"></i></a>
                    <a role="button" class="text-slate-400 hover:text-teal-600 active:text-teal-700" href="'.route('short_url.edit', $url->keyword).'" title="'.__('Edit').'"><i class="fas fa-edit"></i></a>
                    <a role="button" class="text-slate-400 hover:text-red-600 active:text-red-700" href="'.route('dashboard.delete', $url->getRouteKey()).'" title="'.__('Delete').'"><i class="fas fa-trash-alt"></i></a>';
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
                ->title('Short URL')
                ->field('keyword')
                ->sortable()
                ->searchable(),

            Column::add()
                ->title('Original URL')
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
                ->field('action')
                ->searchable(),
        ];
    }
}
