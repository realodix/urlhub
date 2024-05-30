<?php

namespace App\Livewire\Table;

use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;

/**
 * @codeCoverageIgnore
 */
final class UrlListOfUsersTable extends BaseUrlTable
{
    public int $user_id;

    public function getUserIdBuilder(Builder $query): Builder
    {
        return $query->where('urls.user_id', '=', $this->user_id);
    }

    /**
     * @return array<Column>
     */
    public function columns(): array
    {
        return [
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
}
