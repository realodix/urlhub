<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Visit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'url_id',
        'url_author_id',
        'visitor_id',
        'is_first_click',
        'referer',
        'ip',
        'browser',
        'browser_version',
        'device',
        'os',
        'os_version',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_first_click' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo(Url::class);
    }

    /*
    |--------------------------------------------------------------------------
    | General Functions
    |--------------------------------------------------------------------------
    */

    /**
     * Generate unique Visitor Id
     */
    public function visitorId(int $urlId): string
    {
        $visitorId = hash('sha3-256', $urlId.'-'.Request::ip().'-'.Request::userAgent());

        if (Auth::check() === true) {
            $visitorId = (string) Auth::id();
        }

        return $visitorId;
    }

    /**
     * total visit
     */
    public function totalClick(): int
    {
        return self::count();
    }

    /**
     * Total visit by user id
     */
    public function totalClickPerUser(int $authorId = null): int
    {
        return self::whereUrlAuthorId($authorId)->count();
    }

    /**
     * Total visit by URL id
     */
    public function totalClickPerUrl(int $urlId, bool $unique = false): int
    {
        $total = self::whereUrlId($urlId)->count();

        if ($unique) {
            $total = self::whereUrlId($urlId)
                ->whereIsFirstClick(true)
                ->count();
        }

        return $total;
    }
}
