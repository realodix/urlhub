<?php

namespace App\Livewire\Table;

use App\Models\Url;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use LaraDumps\LaraDumps\Livewire\Attributes\Ds;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

/**
 * @codeCoverageIgnore
 */
#[Ds]
final class UrlListTable extends PowerGridComponent
{
    const STR_LIMIT = 80;

    public int $perPage = 25;

    public bool $showUpdateMessages = true;

    public string $sortDirection = 'desc';

    public string $primaryKey = 'urls.id';

    public function setUp(): array
    {
        return [
            Header::make()
                ->showToggleColumns()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage($this->perPage)
                ->showRecordCount('full'),
        ];
    }

    public function datasource(): Builder
    {
        return Url::query()
            ->join('users', function (JoinClause $joinClause) {
                $joinClause->on('urls.user_id', '=', 'users.id');
            })
            ->leftJoin('visits', function (JoinClause $joinClause) {
                $joinClause->on('urls.id', '=', 'visits.url_id');
            })
            ->where('urls.user_id', '!=', Url::GUEST_ID)
            ->select(
                'urls.id as id',
                'users.name as author',
                'urls.title',
                'urls.keyword',
                'urls.destination',
                'urls.created_at',
                DB::raw('COUNT(visits.id) as visits_count'),
                DB::raw('SUM(CASE WHEN visits.is_first_click = 1 THEN 1 ELSE 0 END) as unique_click_count')
            )
            ->groupBy('urls.id', 'users.name', 'urls.title', 'urls.created_at', 'urls.updated_at');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('author', function (Url $url) {
                return view('components.table.author', ['name' => $url->author])
                    ->render();
            })
            ->add('keyword', function (Url $url) {
                return view('components.table.keyword', ['shortUrl' => $url->short_url, 'keyword' => $url->keyword])
                    ->render();
            })
            ->add('destination', function (Url $url) {
                return view('components.table.destination', [
                    'title' => $url->title,
                    'destination' => $url->destination,
                    'limit' => self::STR_LIMIT,
                ])->render();
            })
            ->add('t_clicks', function (Url $url) {
                return view('components.table.visit', ['clicks' => $url->visits_count, 'uniqueClicks' => $url->unique_click_count])
                    ->render();
            })
            ->add('created_at_formatted', function (Url $url) {
                return view('components.table.date-created', ['createdAt' => $url->created_at])
                    ->render();
            })
            ->add('action', function (Url $url) {
                return view('components.table.action-button', ['url' => $url])
                    ->render();
            });
    }

    /**
     * @return array<Column>
     */
    public function columns(): array
    {
        return [
            Column::make('Owner', 'author')
                ->sortable()
                ->searchable(),

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

    public function relationSearch(): array
    {
        return [
            'author' => ['name'],
        ];
    }
}
