<?php

namespace App\Models;

use App\Enums\UserType;
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

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the url that owns the visit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo(Url::class);
    }

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

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
     * Get the top referrers based on visit count.
     *
     * @param \App\Models\User|\App\Models\Url|null $object Object to filter items.
     * @param int $limit The maximum number of top referrers to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTopReferrers($object = null, $limit = 5)
    {
        return self::getTopItems('referer', $object, $limit);
    }

    /**
     * Get the top browsers based on visit count.
     *
     * @param \App\Models\User|\App\Models\Url|null $object Object to filter items.
     * @param int $limit The maximum number of top browsers to return.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTopBrowsers($object = null, $limit = 5)
    {
        return self::getTopItems('browser', $object, $limit);
    }

    /**
     * Get the top operating systems by visit count.
     *
     * @param \App\Models\User|\App\Models\Url|null $object Object to filter items.
     * @param int $limit The maximum number of top operating systems to return.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTopOperatingSystems($object = null, $limit = 5)
    {
        return self::getTopItems('os', $object, $limit);
    }

    /**
     * Get the top items.
     *
     * @param string $column The database column to group by.
     * @param \App\Models\User|\App\Models\Url|null $object Object to filter items.
     * @param int $limit The number of items to return.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private static function getTopItems(string $column, User|Url|null $object, int $limit)
    {
        $query = self::select($column)
            ->selectRaw('count(*) as total')
            ->when($object instanceof User, function ($query) use ($object) {
                $query->whereRelation('url', 'user_id', $object->id);
            })
            ->when($object instanceof Url, function ($query) use ($object) {
                $query->where('url_id', $object->id);
            })
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit($limit);

        return $query->get();
    }
}
