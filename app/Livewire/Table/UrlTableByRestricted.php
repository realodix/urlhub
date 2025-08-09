<?php

namespace App\Livewire\Table;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @codeCoverageIgnore
 */
final class UrlTableByRestricted extends BaseUrlTable
{
    public string $tableName = 'url_table_by_restricted';

    public ?User $author;

    /**
     * @param Builder<\App\Models\Url> $query
     * @return Builder<\App\Models\Url>
     */
    protected function scopeDatasource(Builder $query): Builder
    {
        return $query->where('urls.user_type', UserType::User)
            ->when($this->author instanceof User, function ($query) {
                $query->where('urls.user_id', $this->author->id);
            })
            ->where(function (Builder $query) {
                $query->whereNotNull('password')
                    ->orWhere(fn(Builder $q) => $this->scopeExpired($q));
            });
    }

    /**
     * Applies filters to the query to select expired URLs.
     *
     * This method will filter URLs that have either an expiration date in the past
     * or have reached the click limit set by `expired_clicks`.
     *
     * @param Builder<\App\Models\Url> $query
     */
    private function scopeExpired(Builder $query): void
    {
        $query->where('expires_at', '<', now())
            ->orWhere(function (Builder $q) {
                // Check if the URL has a click limit and if it has been reached
                $q->where('expired_clicks', '>', 0)
                    ->where('urls.expired_clicks', '<=', function ($subQ) {
                        // Count the number of visits and compare
                        $subQ->from('visits')
                            ->selectRaw('count(*)')
                            ->whereColumn('visits.url_id', 'urls.id');
                    });
            });
    }
}
