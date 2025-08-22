<?php

namespace App\Models;

use App\Enums\UserType;
use App\Rules\LinkRules;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property UserType|null $user_type
 * @property string $keyword
 * @property bool $is_custom
 * @property string $destination
 * @property string|null $dest_android
 * @property string|null $dest_ios
 * @property string|null $title
 * @property string|null $password
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property int|null $expired_clicks
 * @property string|null $expired_url
 * @property string|null $expired_notes
 * @property bool $forward_query
 * @property string $user_uid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Visit> $visits
 * @property-read string $short_url
 */
class Url extends Model
{
    /** @use HasFactory<\Database\Factories\UrlFactory> */
    use HasFactory;

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
     * Get the user that created the short URL.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->withDefault(['name' => User::GUEST_NAME]);
    }

    /**
     * Get the visits for the short URL.
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
            set: fn($value) => empty($value) ? User::GUEST_ID : $value,
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
                if (mb_strlen($value) > LinkRules::TITLE_MAX_LENGTH) {
                    return mb_strimwidth($value, 0, LinkRules::TITLE_MAX_LENGTH, '...');
                }

                return $value;
            },
        );
    }

    /**
     * Set the expired_clicks attribute.
     */
    protected function expiredClicks(): Attribute
    {
        return Attribute::make(
            set: fn($value) => empty($value) ? null : $value,
        );
    }

    /**
     * Set the expired_url attribute.
     */
    protected function expiredUrl(): Attribute
    {
        $missing = empty($this->expires_at) && empty($this->expired_clicks);

        return Attribute::make(
            set: fn($value) => $missing ? null : $value,
        );
    }

    /**
     * Set the expired_notes attribute.
     */
    protected function expiredNotes(): Attribute
    {
        $missing = empty($this->expires_at) && empty($this->expired_clicks);

        return Attribute::make(
            set: fn($value) => $missing ? null : $value,
        );
    }

    /**
     * Scope a query to filter URLs by composition and keyword length.
     *
     * @param \Illuminate\Database\Eloquent\Builder<self> $query
     * @param string $composition The type of keyword composition to filter
     * @param int|null $length The length of the keyword
     * @return \Illuminate\Database\Eloquent\Builder<self>
     *
     * @throws \UnhandledMatchError
     */
    public function scopeComposition(Builder $query, string $composition, ?int $length = null): Builder
    {
        $query->when($length > 0, function (Builder $q) use ($length) {
            return $q->whereRaw('LENGTH(keyword) = ?', [$length]);
        });

        match ($composition) {
            // only letters
            'alpha' => $query->whereRegexp('keyword', '^[a-zA-Z]+$'),
            // contains at least one letter and either a number or symbol, but not both.
            'has_num_or_symbol' => $query
                ->whereRegexp('keyword', '[a-zA-Z]')
                ->where(function (Builder $q) {
                    $q->where(function (Builder $subQ) {
                        $subQ->whereRegexp('keyword', '[0-9]')
                            ->whereNotRegexp('keyword', '[-]');
                    })->orWhere(function (Builder $subQ) {
                        $subQ->whereRegexp('keyword', '[-]')
                            ->whereNotRegexp('keyword', '[0-9]');
                    });
                }),
            // contains at least one letter, a number, and symbol.
            'has_num_and_symbol' => $query
                ->whereRegexp('keyword', '[a-zA-Z]')
                ->whereRegexp('keyword', '[0-9]')
                ->whereRegexp('keyword', '[-]'),
            // only numbers and/or symbol, with no letters.
            'only_num_symbol' => $query
                ->whereRegexp('keyword', '^[0-9-]+$'),
        };

        return $query;
    }

    /**
     * Checks if the URL has expired.
     *
     * A URL is considered expired if its expiration date is in the past or
     * if the number of clicks has reached the maximum allowed clicks.
     *
     * @return bool Whether the URL is expired or not.
     */
    public function isExpired(): bool
    {
        $isExpiredAt = $this->expires_at && $this->expires_at->isBefore(now());

        // Use the loaded 'visits_count' attribute if it exists to avoid N+1 queries.
        // The 'visits_count' attribute is automatically loaded by the 'withCount('visits')'
        // method in the BaseUrlTable component.
        $visitsCount = $this->visits_count ?? $this->visits()->count();

        $isExpiredAfterClick = $this->expired_clicks
            && $this->expired_clicks > 0
            && $visitsCount >= $this->expired_clicks;

        return $isExpiredAt || $isExpiredAfterClick;
    }
}
