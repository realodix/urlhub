<?php

namespace App\Models;

use App\Enums\UserType;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $url_id
 * @property UserType $user_type
 * @property bool $is_first_click
 * @property string $referer
 * @property string|null $browser
 * @property string|null $os
 * @property string $user_uid
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Url $urls
 */
class Visit extends Model
{
    /** @use HasFactory<\Database\Factories\VisitFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'user_type' => UserType::class,
            'is_first_click' => 'boolean',
        ];
    }

    /**
     * Get the url that owns the visit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo(Url::class);
    }

    /**
     * Scope a query to only include visits from guest users.
     *
     * @param Builder<self> $query
     */
    public function scopeIsGuest(Builder $query): void
    {
        $query->where('user_type', UserType::Guest)
            ->orWhere('user_type', UserType::Bot);
    }

    /**
     * Check if the visitor has clicked the link before. If the visitor has not
     * clicked the link before, return true.
     *
     * @param Url $url \App\Models\Url
     */
    public function isFirstClick(Url $url): bool
    {
        $hasVisited = $url->visits()
            ->where('user_uid', app(UserService::class)->signature())
            ->exists();

        return $hasVisited ? false : true;
    }
}
