<?php

namespace App\Models;

use App\Models\Traits\Hashidable;
use Embed\Embed;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Url\Url as SpatieUrl;

/**
 * @property int|null $user_id
 * @property string   $short_url
 * @property string   $destination
 * @property string   $title
 * @property int      $clicks
 * @property int      $uniqueClicks
 */
class Url extends Model
{
    use HasFactory;
    use Hashidable;

    const GUEST_ID = null;

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
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest',
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visit()
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

    protected function title(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (config('urlhub.web_title')) {
                    if (Str::startsWith($value, 'http')) {
                        return $this->getWebTitle($value);
                    }

                    return $value;
                }

                return 'No Title';
            },
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
        return self::whereNull('user_id')->count();
    }

    /**
     * Total shortened URLs created
     */
    public function totalUrl(): int
    {
        return self::count();
    }

    /**
     * Total clicks on each shortened URLs
     */
    public function numberOfClicks(int $urlId, bool $unique = false): int
    {
        $total = self::find($urlId)->visit()->count();

        if ($unique) {
            $total = self::find($urlId)->visit()
                ->whereIsFirstClick(true)
                ->count();
        }

        return $total;
    }

    /**
     * Total clicks on all short URLs on each user
     */
    public function numberOfClicksPerUser(int $userId = null): int
    {
        $url = self::whereUserId($userId)->get();

        return $url->sum(fn ($url) => $url->numberOfClicks($url->id));
    }

    /**
     * Total clicks on all short URLs from guest users
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

    /**
     * Fetch the page title from the web page URL
     *
     * @throws \Exception
     */
    public function getWebTitle(string $url): string
    {
        $spatieUrl = SpatieUrl::fromString($url);
        $defaultTitle = $spatieUrl->getHost().' - Untitled';

        try {
            $webTitle = (new Embed)->get($url)->title ?? $defaultTitle;
        } catch (\Exception) {
            // If failed or not found, then return "{domain_name} - Untitled"
            $webTitle = $defaultTitle;
        }

        return $webTitle;
    }
}
