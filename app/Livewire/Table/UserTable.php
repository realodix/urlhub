<?php

namespace App\Livewire\Table;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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
                ->showPerPage(BaseUrlTable::PER_PAGE, BaseUrlTable::PER_PAGE_VALUES)
                ->showRecordCount('full'),
        ];
    }

    /**
     * @return Builder<User>
     */
    public function datasource(): Builder
    {
        return User::withCount('urls');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name', function (User $user) {
                return view('components.table.user-username', ['model' => $user])
                    ->render();
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
