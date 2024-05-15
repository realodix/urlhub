<?php

namespace App\Livewire\Table;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

/**
 * @codeCoverageIgnore
 */
final class UserTable extends PowerGridComponent
{
    public int $perPage = 25;

    public bool $showUpdateMessages = true;

    public string $sortDirection = 'desc';

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
        return User::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name', function (User $user) {
                $urlCountTitle = $user->urls()->count().' '.Str::plural('url', $user->urls()->count()).' created';

                return $user->name.' <span title="'.$urlCountTitle.'">('.numberAbbreviate($user->urls()->count()).')</span>';
            })
            ->add('email')
            ->add('created_at_formatted', function (User $user) {
                return
                    '<span title="'.$user->created_at->toDayDateTimeString().'">'
                        .$user->created_at->shortRelativeDiffForHumans().
                    '</span>';
            })
            ->add('action', function (User $user) {
                return
                    '<a role="button" href="'.route('user.edit', $user).'" title="'.__('Details').'"
                        class="btn btn-secondary btn-sm"
                    >'
                        .Blade::render('@svg(\'icon-person-edit\')').
                    '</a>
                    <a role="button" href="'.route('user.password.show', $user).'" title="'.__('Change Password').'"
                        class="btn btn-secondary btn-sm"
                    >'
                        .Blade::render('@svg(\'icon-key\')').
                    '</a>';
            });
    }

    /**
     * @return array<\PowerComponents\LivewirePowerGrid\Column>
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
