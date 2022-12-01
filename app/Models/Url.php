<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Http\Requests\StoreUrl;
use App\Models\Traits\Hashidable;
use Embed\Embed;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RandomLib\Factory as RandomLibFactory;
use Spatie\Url\Url as SpatieUrl;
use Symfony\Component\HttpFoundation\IpUtils;

/**
 * @property int|null $user_id
 * @property string   $short_url
 * @property string   $long_url
 * @property string   $meta_title
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
        'long_url',
        'meta_title',
        'clicks',
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
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest',
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\Relation
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
            set: function ($value) {
                return $value === 0 ? self::GUEST_ID : $value;
            },
        );
    }

    protected function longUrl(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => rtrim($value, '/'),
        );
    }

    protected function metaTitle(): Attribute
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

    protected function shortUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => url('/'.$attributes['keyword']),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Other Functions
    |--------------------------------------------------------------------------
    */

    /**
     * @param StoreUrl        $request \App\Http\Requests\StoreUrl
     * @param int|string|null $userId  Jika user_id tidak diisi, maka akan diisi
     *                                 null. Ini terjadi karena guest yang membuat
     *                                 URL. Lihat setUserIdAttribute().
     * @return self
     */
    public function shortenUrl(StoreUrl $request, $userId)
    {
        $key = $request['custom_key'] ?? $this->urlKey($request['long_url']);

        return Url::create([
            'user_id'    => $userId,
            'long_url'   => $request['long_url'],
            'meta_title' => $request['long_url'],
            'keyword'    => $key,
            'is_custom'  => $request['custom_key'] ? true : false,
            'ip'         => $request->ip(),
        ]);
    }

    /**
     * @param int|string|null $userId \Illuminate\Contracts\Auth\Guard::id()
     * @return bool \Illuminate\Database\Eloquent\Model::save()
     */
    public function duplicate(string $key, $userId, string $randomKey = null)
    {
        $randomKey = $randomKey ?? $this->randomString();
        $shortenedUrl = self::whereKeyword($key)->firstOrFail();

        $replicate = $shortenedUrl->replicate()->fill([
            'user_id'   => $userId,
            'keyword'   => $randomKey,
            'is_custom' => 0,
            'clicks'    => 0,
        ]);

        return $replicate->save();
    }

    public function urlKey(string $url): string
    {
        $length = config('urlhub.hash_length') * -1;

        // Step 1
        // Generate unique key from truncated long URL.
        $uniqueUrlKey = substr(preg_replace('/[^a-z0-9]/i', '', $url), $length);
        // $uniqueUrlKey = substr(preg_replace('/[^a-z0-9]/i', '', $url), -5);

        // Step 2
        // If the unique key in step 1 is not available (already used), then generate a
        // random string.
        $generatedRandomKey = $this->urlKeyIsUnique($uniqueUrlKey);

        while ($generatedRandomKey) {
            $uniqueUrlKey = $this->randomString();
            $generatedRandomKey = $this->urlKeyIsUnique($uniqueUrlKey);
        }

        return $uniqueUrlKey;
    }

    private function urlKeyIsUnique(string $url): bool
    {
        $generatedRandomKey = self::whereKeyword($url)->first();
        $reservedKeyword = in_array($url, config('urlhub.reserved_keyword'));
        $reservedRoutes = in_array(
            $url,
            array_map(
                fn (\Illuminate\Routing\Route $route) => $route->uri,
                \Route::getRoutes()->get()
            )
        );

        if ($generatedRandomKey || $reservedKeyword || $reservedRoutes) {
            return true;
        }

        return false;
    }

    /**
     * The number of unique random strings used as the short url key.
     *
     * Calculation formula:
     * keyUsed = randomKey + customKey
     *
     * The generated character length for "customKey" should be similar to "randomKey".
     */
    public function keyUsed(): int
    {
        $hashLength = (int) config('urlhub.hash_length');
        $regexPattern = '['.config('urlhub.hash_char').']{'.$hashLength.'}';

        $randomKey = self::whereIsCustom(false)
            ->whereRaw('LENGTH(keyword) = ?', [$hashLength])
            ->count();

        $customKey = self::whereIsCustom(true)
            ->whereRaw('LENGTH(keyword) = ?', [$hashLength])
            ->whereRaw("keyword REGEXP '".$regexPattern."'")
            ->count();

        return $randomKey + $customKey;
    }

    /**
     * Counts the maximum number of unique random strings that can be generated.
     */
    public function keyCapacity(): int
    {
        $alphabet = strlen(config('urlhub.hash_char'));
        $length = config('urlhub.hash_length');

        // for testing purposes only
        // tests\Unit\Middleware\UrlHubLinkCheckerTest.php
        if ($length === 0) {
            return 0;
        }

        return (int) pow($alphabet, $length);
    }

    /**
     * Counts unique random strings that can be generated.
     *
     * https://www.php.net/manual/en/function.max.php
     */
    public function keyRemaining(): int
    {
        $keyCapacity = $this->keyCapacity();
        $keyUsed = $this->keyUsed();

        return max($keyCapacity - $keyUsed, 0);
    }

    public function keyRemainingInPercent(int $precision = 2): string
    {
        $capacity = $this->keyCapacity();
        $remaining = $this->keyRemaining();
        $result = round(($remaining / $capacity) * 100, $precision);

        $lowerBoundInPercent = 1 / (10 ** $precision);
        $upperBoundInPercent = 100 - $lowerBoundInPercent;
        $lowerBound = $lowerBoundInPercent / 100;
        $upperBound = 1 - $lowerBound;

        if ($remaining > 0 && $remaining < ($capacity * $lowerBound)) {
            $result = $lowerBoundInPercent;
        } elseif ($remaining > ($capacity * $upperBound)) {
            $result = $upperBoundInPercent;
        }

        return $result.'%';
    }

    /**
     * Count the number of URLs based on user id.
     *
     * @param int|string|null $userId
     */
    public function urlCount($userId = null): int
    {
        return self::whereUserId($userId)->count('keyword');
    }

    public function totalUrl(): int
    {
        return self::count('keyword');
    }

    /**
     * Count the number of clicks based on user id.
     *
     * @param int|string|null $userId
     */
    public function clickCount($userId = null): int
    {
        return self::whereUserId($userId)->sum('clicks');
    }

    public function totalClick(): int
    {
        return self::sum('clicks');
    }

    /**
     * Anonymize an IPv4 or IPv6 address.
     *
     * @param string|null $address
     */
    public static function anonymizeIp($address): string
    {
        if (config('urlhub.anonymize_ip_addr') === false) {
            return $address;
        }

        return IPUtils::anonymize($address);
    }

    /**
     * Get the domain from external url.
     */
    public function getDomain(string $url): string
    {
        $url = SpatieUrl::fromString($url);

        return Helper::urlSanitize($url->getHost());
    }

    /**
     * This function returns a string: either the page title as defined in HTML,
     * or "{domain_name} - Untitled" if not found.
     *
     * @throws \Exception
     */
    public function getWebTitle(string $url): string
    {
        $domain = $this->getDomain($url);

        try {
            $webTitle = (new Embed)->get($url)->title;
        } catch (\Exception) {
            $webTitle = $domain.' - Untitled';
        }

        return $webTitle;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function randomString()
    {
        $alphabet = config('urlhub.hash_char');
        $length = config('urlhub.hash_length');
        $factory = new RandomLibFactory;

        return $factory->getMediumStrengthGenerator()->generateString($length, $alphabet);
    }
}
