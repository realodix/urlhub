<?php

namespace App\Livewire\Table;

use Illuminate\Database\Eloquent\Builder;

/**
 * @codeCoverageIgnore
 */
final class UrlTableByUser extends BaseUrlTable
{
    public string $tableName = 'url_table_by_user';

    public ?int $user_id = null;

    /**
     * @param Builder<\App\Models\Url> $query
     * @return Builder<\App\Models\Url>
     */
    protected function scopeDatasource(Builder $query): Builder
    {
        return $query->where('urls.user_id', $this->user_id);
    }
}
