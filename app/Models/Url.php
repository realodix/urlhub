<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property UserType $user_type
 * @property string $keyword
 * @property bool $is_custom
 * @property string $destination
 * @property string|null $dest_android
 * @property string|null $dest_ios
 * @property string|null $title
 * @property string|null $password
 * @property \Carbon\Carbon|null $expires_at
 * @property int|null $expired_clicks
 * @property string|null $expired_url
 * @property string|null $expired_notes
 * @property bool $forward_query
 * @property string $user_uid
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read User $author
 * @property-read Visit $visits
 * @property-read string $short_url
 */
class Url extends Model
{
    /** @use HasFactory<\Database\Factories\UrlFactory> */
    use HasFactory;

    /** @var null */
    const GUEST_ID = null;

    /** @var int */
    const TITLE_LENGTH = 255;

    /**
     * The minimum length of the password.
     *
     * @var int
     */
    const PWD_MIN_LENGTH = 3;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'user_type' => UserType::class,
            'is_custom' => 'boolean',
            'forward_query' => 'boolean',
            'password' => 'hashed',
            'expires_at' => 'datetime',
            'expired_clicks' => 'integer',
        ];
    }

    /**
     * Get the user that owns the Url.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->withDefault([
                'name' => 'Guest Author',
            ]);
    }

    /**
     * Get the visits for the Url.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Visit, $this>
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    protected function userId(): Attribute
    {
        return Attribute::make(
            set: fn($value) => empty($value) ? self::GUEST_ID : $value,
        );
    }

    protected function shortUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attr) => url('/'.$attr['keyword']),
        );
    }

    protected function destination(): Attribute
    {
        return Attribute::make(
            set: fn($value) => rtrim($value, '/'),
        );
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (mb_strlen($value) > self::TITLE_LENGTH) {
                    return mb_strimwidth($value, 0, self::TITLE_LENGTH, '...');
                }

                return $value;
            },
        );
    }

    /**
     * Scope a query to filter URLs by composition and keyword length.
     *
     * @param Builder<self> $query
     * @param string $composition The type of keyword composition to filter
     * @param int|null $length The length of the keyword
     * @return Builder<self>
     */
    public function scopeComposition(Builder $query, string $composition, ?int $length = null): Builder
    {
        // 1. Filter by length if specified
        $query->when($length > 0, function (Builder $q) use ($length) {
            return $q->whereRaw('LENGTH(keyword) = ?', [$length]);
        });

        // 2. Filter by composition
        match ($composition) {
            // only letters
            'alpha' => $query->where('keyword', 'REGEXP', '^[a-zA-Z]+$'),
            // contains at least one letter and either a number or symbol, but not both.
            'has_num_or_symbol' => $query
                ->where('keyword', 'REGEXP', '[a-zA-Z]')
                ->where(function (Builder $q) {
                    $q->where(function (Builder $subQ) {
                        $subQ->where('keyword', 'REGEXP', '[0-9]')
                            ->where('keyword', 'NOT REGEXP', '[-]');
                    })->orWhere(function (Builder $subQ) {
                        $subQ->where('keyword', 'REGEXP', '[-]')
                            ->where('keyword', 'NOT REGEXP', '[0-9]');
                    });
                }),
            // contains at least one letter, a number, and symbol.
            'has_num_and_symbol' => $query
                ->where('keyword', 'REGEXP', '[a-zA-Z]')
                ->where('keyword', 'REGEXP', '[0-9]')
                ->where('keyword', 'REGEXP', '[-]'),
            // only numbers and/or symbol, with no letters.
            'only_num_symbol' => $query
                ->where('keyword', 'REGEXP', '^[0-9-]+$'),
        };

        return $query;
    }

    /**
     * Determine if the URL is expired
     *
     * - It checks if the 'expires_at' field is set and if it is before
     *   the current time.
     * - It also checks if the number of clicks is greater than or equal to
     *   the 'expired_clicks' field.
     *
     * @return bool Whether the URL is expired or not
     */
    public function isExpired(): bool
    {
        $isExpiredAt = $this->expires_at && $this->expires_at->isBefore(now());
        $isExpiredAfterClick = $this->expired_clicks
            && $this->expired_clicks > 0
            && $this->visits()->count() >= $this->expired_clicks;

        return $isExpiredAt || $isExpiredAfterClick;
    }
}
