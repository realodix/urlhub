<?php

namespace App\Livewire\Table;

use App\Models\Url;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;

/**
 * @codeCoverageIgnore
 */
final class UrlListTable extends BaseUrlTable
{
    /** @var int */
    const STR_LIMIT = 80;

    public string $tableName = 'all_urls_table';

    public function getUserIdBuilder(Builder $query): Builder
    {
        return $query->where('urls.user_id', '!=', Url::GUEST_ID);
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
            Column::make('Clicks', 'visits_count')
                ->sortable(),
            Column::make('CREATED AT', 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),
            Column::make('ACTIONS', 'action'),
        ];
    }
}
