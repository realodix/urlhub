<?php

namespace App\Livewire\Table;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

/**
 * @codeCoverageIgnore
 */
final class UserTable extends PowerGridComponent
{
    public bool $showUpdateMessages = true;

    public string $sortDirection = 'desc';

    public string $tableName = 'user-table';

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
        return User::withCount('urls');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name', function (User $user) {
                $urlCount = $user->urls_count;
                $urlCountTitle = number_format($urlCount).' short '.Str::plural('link', $urlCount);

                return $user->name.' <span title="'.$urlCountTitle.'" class="dark:text-dark-400">('.n_abb($urlCount).')</span>';
            })
            ->add('email')
            ->add('created_at_formatted', function (User $user) {
                $date = $user->created_at->inUserTz();
                $offset = '('.$date->getOffsetString().')';

                return
                    '<span title="'.$date->toDayDateTimeString().' '.$offset.'" class="dark:text-dark-400">'
                        .$date->shortRelativeDiffForHumans().
                    '</span>';
            })
            ->add('action', function (User $user) {
                return view('components.table.action-button_user', [
                    'model' => $user,
                    'detail_link' => route('user.edit', $user),
                    'cp_link' => route('user.password.show', $user),
                    'delete_link' => route('user.delete.confirm', $user),
                ])->render();
            });
    }

    /**
     * @return array<Column>
     */
    public function columns(): array
    {
        return [
            Column::make('USERNAME', 'name')
                ->sortable()->searchable()
                ->contentClassField('dark:text-dark-300'),

            Column::make('EMAIL', 'email')
                ->sortable()->searchable()
                ->contentClassField('dark:text-dark-300'),

            Column::make('CREATED AT', 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),

            Column::make('ACTIONS', 'action')
                ->bodyAttribute(styleAttr: ';padding-left: 8px'),
        ];
    }
}
