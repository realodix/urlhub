<?php

namespace App\Http\Livewire\Table;

use App\Helpers\Helper;
use App\Models\Url;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{
    Column, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

/**
 * @codeCoverageIgnore
 */
final class AllUlrTable extends PowerGridComponent
{
    use ActionButton;

    const STR_LIMIT = 60;

    public bool $showUpdateMessages = true;

    public string $sortDirection = 'desc';

    /*
    |--------------------------------------------------------------------------
    | Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        return [
            Header::make()
                ->showToggleColumns()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount('full'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */
    public function datasource(): ?Builder
    {
        return Url::query();
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Search
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
    | Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('user_name', function (Url $url) {
                return '<span class="font-semibold">'.$url->author->name.'</span>';
            })
            ->addColumn('keyword', function (Url $url) {
                return
                    '<a href="'.$url->short_url.'" target="_blank" class="font-light text-indigo-700">'.$url->keyword.'</a>'
                    .Blade::render('@svg(\'icon-open-in-new\', \'!h-[0.7em] ml-1\')');
            })
            ->addColumn('destination', function (Url $url) {
                return
                    '<span title="'.htmlspecialchars($url->title).'">'
                        .Str::limit($url->title, self::STR_LIMIT).
                    '</span>
                    <br>
                    <a href="'.$url->destination.'" target="_blank" title="'.$url->destination.'" rel="noopener noreferrer"
                        class="text-slate-500"
                    >'
                        .Helper::urlDisplay($url->destination, self::STR_LIMIT)
                        .Blade::render('@svg(\'icon-open-in-new\', \'!h-[0.7em] ml-1\')').
                    '</a>';
            })
            ->addColumn('t_clicks', function (Url $url) {
                $uClick = Helper::compactNumber($url->uniqueClicks);
                $tClick = Helper::compactNumber($url->clicks);
                $icon = Blade::render('@svg(\'icon-bar-chart\', \'ml-2 text-indigo-600\')');
                $title = $uClick.' '.__('Uniques').' / '.$tClick.' '.__('Clicks');

                return '<div title="'.$title.'">'.$uClick.' / '.$tClick.$icon.'</div>';
            })
            ->addColumn('created_at_formatted', function (Url $url) {
                return
                    '<span title="'.$url->created_at->toDayDateTimeString().'">'
                        .$url->created_at->shortRelativeDiffForHumans().
                    '</span>';
            })
            ->addColumn('action', function (Url $url) {
                return
                    '<a role="button" href="'.route('su_detail', $url->keyword).'" target="_blank" title="'.__('Open front page').'"
                        class="btn-icon btn-icon-table"
                    >'
                        .Blade::render('@svg(\'icon-open-in-new\')').
                    '</a>
                    <a role="button" href="'.route('dashboard.su_duplicate', $url->keyword).'" title="'.__('Duplicate').'"
                        class="btn-icon btn-icon-table"
                    >'
                        .Blade::render('@svg(\'icon-clone-alt\')').
                    '</a>
                    <a role="button" href="'.route('dashboard.su_edit', $url->keyword).'" title="'.__('Edit').'"
                        class="btn-icon btn-icon-table"
                    >'
                        .Blade::render('@svg(\'icon-edit-alt\')').
                    '</a>
                    <a role="button" href="'.route('dashboard.su_delete', $url->getRouteKey()).'" title="'.__('Delete').'"
                        class="btn-icon btn-icon-table-delete"
                    >'
                        .Blade::render('@svg(\'icon-trash-alt\')').
                    '</a>';
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Include Columns
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
            Column::make('Owner', 'user_name', 'users.name')
                ->sortable()
                ->searchable(),

            Column::make('Short URL', 'keyword')
                ->sortable()
                ->searchable(),

            Column::make('Destination URL', 'destination')
                ->sortable()
                ->searchable(),

            Column::make('CLICKS', 't_clicks')
                ->bodyAttribute(styleAttr: ';padding-left: 8px'),

            Column::make('CREATED AT', 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),

            Column::make('ACTIONS', 'action')
                ->bodyAttribute(styleAttr: ';padding-left: 8px'),
        ];
    }
}
