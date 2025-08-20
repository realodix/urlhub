<?php

namespace App\Services;

use App\Enums\UserType;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Support\Uri;

class VisitService
{
    /**
     * Get the referer host.
     *
     * This method ensures that only the base host URL (e.g., 'https://example.com')
     * is returned, stripping any paths or query parameters.
     */
    public function getRefererHost(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $referer = Uri::of($value);

        return $referer->scheme().'://'.$referer->host();
    }

    /**
     * Counts the total number of visits on short links created by registered
     * users.
     *
     * This aggregates all clicks associated with URLs where the `user_type` is
     * `User`.
     */
    public function visitsOnUserLinks(): int
    {
        return Visit::whereRelation('url', 'user_type', UserType::User)
            ->count();
    }

    /**
     * Counts the total number of visits on short links created by guest users.
     *
     * This aggregates all clicks associated with URLs where the `user_type` is
     * `Guest`.
     */
    public function visitsOnGuestLinks(): int
    {
        return Visit::whereRelation('url', 'user_type', UserType::Guest)
            ->count();
    }

    /**
     * Counts the total number of visits by registered users.
     */
    public function userVisits(bool $unique = false): int
    {
        return Visit::where('user_type', UserType::User)
            ->when($unique, fn($query) => $query->distinct('user_uid'))
            ->count();
    }

    /**
     * Counts the total number of visits by guest users.
     *
     * @param bool $unique Whether to count unique guest users or all guest visits.
     * @return int
     */
    public function guestVisits(bool $unique = false)
    {
        return Visit::isGuest()
            ->when($unique, fn($query) => $query->distinct('user_uid'))
            ->count();
    }

    /**
     * Counts the total number of unique visitors across all links.
     */
    public function visitors(): int
    {
        return Visit::distinct('user_uid')->count();
    }

    /**
     * Counts the total number of unique users visitors.
     */
    public function userVisitors(): int
    {
        return $this->userVisits(true);
    }

    /**
     * Counts the total number of unique guest visitors.
     */
    public function guestVisitors(): int
    {
        return $this->guestVisits(true);
    }

    /**
     * Get the top referrers based on visit count.
     *
     * @param \App\Models\User|\App\Models\Url|null $object Object to filter items.
     * @param int $limit The maximum number of top referrers to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function topReferrers($object = null, $limit = 5)
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
    public function topBrowsers($object = null, $limit = 5)
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
    public function topOperatingSystems($object = null, $limit = 5)
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
        $query = Visit::select($column)
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
