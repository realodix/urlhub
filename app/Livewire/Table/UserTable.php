<?php

namespace App\Livewire\Table;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use PowerComponents\LivewirePowerGrid\{
    Column, Footer, Header, PowerGrid, PowerGridColumns, PowerGridComponent};

/**
 * @codeCoverageIgnore
 */
final class UserTable extends PowerGridComponent
{
    public bool $showUpdateMessages = true;

    public string $sortDirection = 'desc';

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

    public function datasource(): ?Builder
    {
        return User::query();
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('name', function (User $user) {
                $urlCountTitle = $user->urls()->count().' '.Str::plural('url', $user->urls()->count()).' created';

                return $user->name.' <span title="'.$urlCountTitle.'">('.$user->urls()->count().')</span>';
            })
            ->addColumn('email')
            ->addColumn('created_at_formatted', function (User $user) {
                return
                    '<span title="'.$user->created_at->toDayDateTimeString().'">'
                        .$user->created_at->shortRelativeDiffForHumans().
                    '</span>';
            })
            ->addColumn('action', function (User $user) {
                return
                    '<a role="button" href="'.route('user.edit', $user).'" title="'.__('Details').'"
                        class="btn btn-secondary btn-sm"
                    >'
                        .Blade::render('@svg(\'icon-user-edit\')').
                    '</a>
                    <a role="button" href="'.route('user.change-password', $user).'" title="'.__('Change Password').'"
                        class="btn btn-secondary btn-sm"
                    >'
                        .Blade::render('@svg(\'icon-key\')').
                    '</a>';
            });
    }

    /**
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('USERNAME', 'name')
                ->sortable()
                ->searchable(),

            Column::make('EMAIL', 'email')
                ->sortable()
                ->searchable(),

            Column::make('CREATED AT', 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),

            Column::make('ACTIONS', 'action')
                ->bodyAttribute(styleAttr: ';padding-left: 8px'),
        ];
    }
}
