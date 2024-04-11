<?php

namespace App\Models;

use App\Http\Requests\StoreUrl;
use App\Services\KeyGeneratorService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * @property int            $id
 * @property int|null       $user_id
 * @property string         $keyword
 * @property bool           $is_custom
 * @property string         $destination
 * @property string         $title
 * @property string         $user_sign
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property User           $author
 * @property Visit          $visits
 * @property string         $short_url
 * @property int            $clicks
 * @property int            $uniqueClicks
 */
class Url extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'user_sign',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id'   => 'integer',
            'is_custom' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the user that owns the Url.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')
            ->withDefault([
                'name' => self::GUEST_NAME,
            ]);
    }

    /**
     * Get the visits for the Url.
     */
    public function visits(): HasMany
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
    | General
    |--------------------------------------------------------------------------
    */

    public function getKeyword(StoreUrl $request): string
    {
        $keyGen = app(KeyGeneratorService::class);

        return $request->custom_key ?? $keyGen->generate($request->long_url);
    }

    public function getWebTitle(string $webAddress): string
    {
        $spatieUrl = \Spatie\Url\Url::fromString($webAddress);
        $defaultTitle = $spatieUrl->getHost().' - Untitled';

        if (config('urlhub.web_title')) {
            try {
                $title = app(\Embed\Embed::class)->get($webAddress)->title ?? $defaultTitle;
            } catch (\Exception) {
                // If failed or not found, then return "{domain_name} - Untitled"
                $title = $defaultTitle;
            }

            return $title;
        }

        return 'No Title';
    }

    /**
     * The number of shortened URLs that have been created by each User
     *
     * @param int $userId The ID of the author of the shortened URL
     */
    public function numberOfUrls(int $userId): int
    {
        return self::whereUserId($userId)->count();
    }

    /**
     * The total number of shortened URLs that have been created by all guest
     * users
     */
    public function numberOfUrlsByGuests(): int
    {
        return self::whereNull('user_id')->count();
    }

    /**
     * Total clicks on each shortened URLs
     *
     * @param int  $urlId  The ID of the shortened URL
     * @param bool $unique If true, only count unique clicks
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
        // If the user is logged in, get the total clicks on all short URLs from
        // the user
        $authorId = auth()->check() ? auth()->id() : $this->author->id;
        $url = self::whereUserId($authorId)->get();

        return $url->sum(fn ($url) => $url->numberOfClicks($url->id));
    }

    /**
     * Total clicks on all short URLs from all guest users
     */
    public function numberOfClicksFromGuests(): int
    {
        $url = self::whereNull('user_id')->get();

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
