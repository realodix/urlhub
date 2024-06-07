<?php

namespace App\Livewire\Table;

use App\Models\Url;
use Illuminate\Database\Eloquent\Builder;

/**
 * @codeCoverageIgnore
 */
final class UrlListOfGuestTable extends BaseUrlTable
{
    public function getUserIdBuilder(Builder $query): Builder
    {
        return $query->where('urls.user_id', Url::GUEST_ID);
    }
}
