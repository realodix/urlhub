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
 * @property string $user_uid
 * @property bool $is_first_click
 * @property string $referer
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property Url $urls
 */
class Visit extends Model
{
    /** @use HasFactory<\Database\Factories\VisitFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'url_id',
        'user_type',
        'user_uid',
        'is_first_click',
        'referer',
    ];

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
     * The number of clicks from links created by the currently authenticated
     * user.
     */
    public function authUserLinkVisits(): int
    {
        return self::whereHas('url', function ($query) {
            $query->where('user_id', auth()->id());
        })->count();
    }

    /**
     * Jumlah klik dari link yang dibuat oleh semua user
     */
    public function userLinkVisitCount(): int
    {
        return self::whereHas('url', function ($query) {
            $query->where('user_type', UserType::User);
        })->count();
    }

    /**
     * Jumlah klik dari link yang dibuat oleh semua guest user
     */
    public function guestUserLinkVisitCount(): int
    {
        return self::whereHas('url', function ($query) {
            $query->where('user_type', UserType::Guest);
        })->count();
    }

    /**
     * Jumlah klik yang dibuat oleh semua user
     */
    public function userVisitCount(): int
    {
        return self::where('user_type', UserType::User)->count();
    }

    /**
     * Jumlah klik yang dibuat oleh semua guest user
     */
    public function guestVisitCount(): int
    {
        return self::isGuest()->count();
    }

    /**
     * Jumlah klik yang dibuat oleh semua guest user yang unik
     */
    public function uniqueGuestVisitCount(): int
    {
        return self::isGuest()
            ->distinct('user_uid')
            ->count();
    }
}
