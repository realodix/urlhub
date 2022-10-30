<?php

namespace App\Http\Livewire;

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
                /** @var \App\Models\User */
                $user = $url->user;

                return '<span class="font-semibold">'.$user->name.'</span>';
            })
            ->addColumn('keyword', function (Url $url) {
                return
                    '<a href="'.$url->short_url.'" target="_blank" class="font-semibold">'.$url->keyword.'</a>'
                    .Blade::render('@svg(\'icon-open-in-new\', \'!h-[0.7em] ml-1\')');
            })
            ->addColumn('long_url', function (Url $url) {
                return
                    '<span title="'.$url->meta_title.'" class="font-semibold">'
                        .Str::limit($url->meta_title, 80).
                    '</span>
                    <br>
                    <a href="'.$url->long_url.'" target="_blank" title="'.$url->long_url.'" rel="noopener noreferrer" class="text-slate-500">'
                        .Helper::urlDisplay($url->long_url, false, 70)
                        .Blade::render('@svg(\'icon-open-in-new\', \'!h-[0.7em] ml-1\')').
                    '</a>';
            })
            ->addColumn('clicks', fn (Url $url) => $url->clicks.Blade::render('@svg(\'icon-bar-chart\', \'ml-2\')'))
            ->addColumn('created_at_formatted', function (Url $url) {
                /** @var \Carbon\Carbon */
                $urlCreatedAt = $url->created_at;

                return
                    '<span title="'.$urlCreatedAt->toDayDateTimeString().'">'
                        .$urlCreatedAt->diffForHumans().
                    '</span>';
            })
            ->addColumn('action', function (Url $url) {
                return
                    '<a role="button" href="'.route('short_url.stats', $url->keyword).'" target="_blank" title="'.__('Open front page').'" class="btn-icon btn-action">'
                        .Blade::render('@svg(\'icon-open-in-new\')').
                    '</a>
                    <a role="button" href="'.route('dashboard.duplicate', $url->keyword).'" title="'.__('Duplicate').'" class="btn-icon btn-action">'
                        .Blade::render('@svg(\'icon-clone-alt\')').
                    '</a>
                    <a role="button" href="'.route('short_url.edit', $url->keyword).'" title="'.__('Edit').'" class="btn-icon btn-action">'
                        .Blade::render('@svg(\'icon-edit-alt\')').
                    '</a>
                    <a role="button" href="'.route('dashboard.delete', $url->getRouteKey()).'" title="'.__('Delete').'" class="btn-icon btn-action-delete">'
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
