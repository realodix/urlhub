<?php

namespace App\Http\Livewire;

use App\Models\User;
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
final class UserTable extends PowerGridComponent
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
    public function setUp(): void
    {
        $this->showRecordCount('full')
            ->showPerPage()
            ->showSearchInput();
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
        return User::query();
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
    public function addColumns(): ?PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('name', function (User $user) {
                $urlCount = $user->url()->count();
                $urlCountTitle = $urlCount.' '.Str::plural('url', $urlCount).' created';

                return $user->name.' <span title="'.$urlCountTitle.'">('.$urlCount.')</span>';
            })
            ->addColumn('email')
            ->addColumn('created_at_formatted', function (User $user) {
                return
                    '<span title="'.$user->created_at->toDayDateTimeString().'">'
                        .$user->created_at->diffForHumans().
                    '</span>';
            })
            ->addColumn('action', function (User $user) {
                return
                    '<a role="button" href="'.route('user.edit', $user->name).'" title="'.__('Details').'" class="btn-icon btn-action">'
                        .Blade::render('@svg(\'icon-user-edit\')').
                    '</a>
                    <a role="button" href="'.route('user.change-password', $user->name).'" title="'.__('Change Password').'" class="btn-icon btn-action">'
                        .Blade::render('@svg(\'icon-key\')').
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
                ->title('USERNAME')
                ->field('name')
                ->sortable()
                ->searchable(),

            Column::add()
                ->title('EMAIL')
                ->field('email')
                ->sortable()
                ->searchable(),

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
