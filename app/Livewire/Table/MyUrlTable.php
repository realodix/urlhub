<?php

namespace App\Livewire\Table;

use Illuminate\Database\Eloquent\Builder;

/**
 * @codeCoverageIgnore
 */
final class MyUrlTable extends BaseUrlTable
{
    public string $tableName = 'my-url-table';

    public function getUserIdBuilder(Builder $query): Builder
    {
        return $query->where('urls.user_id', auth()->id());
    }
}
