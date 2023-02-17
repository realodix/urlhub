<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property User           $author
 * @property Visit          $visits
 * @property int|null       $user_id
 * @property string         $short_url
 * @property string         $destination
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int            $clicks
 * @property int            $uniqueClicks
 */
class Url extends Model
{
    use \App\Models\Traits\Hashidable;
    use HasFactory;

    const GUEST_ID = null;

    const GUEST_NAME = 'Guest';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'keyword',
        'is_custom',
        'destination',
        'title',
        'ip',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id'   => 'integer',
        'is_custom' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->withDefault([
                'name' => self::GUEST_NAME,
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    protected function userId(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value === 0 ? self::GUEST_ID : $value,
        );
    }

    protected function shortUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attr) => url('/'.$attr['keyword']),
        );
    }

    protected function destination(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => rtrim($value, '/'),
        );
    }

    protected function clicks(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attr) => $this->numberOfClicks($attr['id']),
        );
    }

    protected function uniqueClicks(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attr) => $this->numberOfClicks($attr['id'], unique: true),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include guest users.
     */
    public function scopeByGuests(Builder $query): Builder
    {
        return $query->whereNull('user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | General Functions
    |--------------------------------------------------------------------------
    */

    /**
     * The number of shortened URLs that have been created by each User
     */
    public function numberOfUrls(int $userId): int
    {
        return self::whereUserId($userId)->count();
    }

    /**
     * The total number of shortened URLs that have been created by guests
     */
    public function numberOfUrlsByGuests(): int
    {
        return self::byGuests()->count();
    }

    /**
     * Total clicks on each shortened URLs
     */
    public function numberOfClicks(int $urlId, bool $unique = false): int
    {
        /** @var self */
        $self = self::find($urlId);
        $total = $self->visits()->count();

        if ($unique === true) {
            $total = $self->visits()
                ->whereIsFirstClick(true)
                ->count();
        }

        return $total;
    }

    /**
     * Total clicks on all short URLs on each user
     */
    public function numberOfClicksPerAuthor(): int
    {
        // If the user is logged in, get the total clicks on all short URLs from the user
        $authorId = auth()->check() ? auth()->id() : $this->author->id;
        $url = self::whereUserId($authorId)->get();

        return $url->sum(fn ($url) => $url->numberOfClicks($url->id));
    }

    /**
     * Total clicks on all short URLs from guest users
     */
    public function numberOfClicksFromGuests(): int
    {
        $url = self::byGuests()->get();

        return $url->sum(fn ($url) => $url->numberOfClicks($url->id));
    }

    /**
     * Total clicks on all shortened URLs
     */
    public function totalClick(): int
    {
        return Visit::count();
    }
}
