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
     * Scope a query to only include visits from specific user type.
     *
     * @param Builder<self> $query \Illuminate\Database\Eloquent\Builder
     * @param UserType $type \App\Enums\UserType
     */
    public function scopeUserType(Builder $query, UserType $type): void
    {
        $query->where('user_type', $type);
    }

    /**
     * Number of link visits from the currently logged-in user.
     */
    public function authUserLinkVisitCount(): int
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
            $query->where('user_type', UserType::User);
        })->count();
    }

    public function userVisitCount(): int
    {
        return self::userType(UserType::User)->count();
    }

    /**
     * Number of guest user link visits.
     */
    public function guestUserLinkVisitCount(): int
    {
        return self::whereHas('url', function ($query) {
            $query->where('user_type', UserType::Guest);
        })->count();
    }

    public function guestVisitCount(): int
    {
        return self::userType(UserType::Guest)->count();
    }

    public function uniqueGuestVisitCount(): int
    {
        return self::userType(UserType::Guest)
            ->distinct('user_uid')
            ->count();
    }
}
