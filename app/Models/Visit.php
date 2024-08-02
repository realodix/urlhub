<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $url_id
 * @property string $visitor_id
 * @property bool $is_first_click
 * @property string $referer
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property Url $urls
 */
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
        'visitor_id',
        'is_first_click',
        'referer',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{is_first_click:'boolean'}
     */
    protected function casts(): array
    {
        return [
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
     * Number of current user link visits.
     */
    public function currentUserLinkVisitCount(): int
    {
        return self::whereHas('url', function ($query) {
            $query->where('user_id', auth()->id());
        })->count();
    }

    /**
     * Number of user link visits.
     */
    public function userLinkVisitCount(): int
    {
        return self::whereHas('url', function ($query) {
            $query->where('user_id', '!=', Url::GUEST_ID);
        })->count();
    }

    /**
     * Number of guest user link visits.
     */
    public function guestUserLinkVisitCount(): int
    {
        return self::whereHas('url', function ($query) {
            $query->where('user_id', Url::GUEST_ID);
        })->count();
    }
}
