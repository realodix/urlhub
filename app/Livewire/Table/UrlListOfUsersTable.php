<?php

namespace App\Livewire\Table;

use Illuminate\Database\Eloquent\Builder;

/**
 * @codeCoverageIgnore
 */
final class UrlListOfUsersTable extends BaseUrlTable
{
    public int $user_id;

    public string $tableName = 'url_list_of_users_table';

    public function getUserIdBuilder(Builder $query): Builder
    {
        return $query->where('urls.user_id', $this->user_id);
    }
}
