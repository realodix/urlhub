<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Http\Requests\StoreUrl;
use App\Models\Traits\Hashidable;
use Embed\Embed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RandomLib\Factory as RandomLibFactory;
use Spatie\Url\Url as SpatieUrl;
use Symfony\Component\HttpFoundation\IpUtils;

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
    | Database tables are often related to one another. Eloquent relationships
    | are defined as methods on Eloquent model classes.
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
    | Eloquent: Mutators
    |--------------------------------------------------------------------------
    |
    | Accessors and mutators allow you to format Eloquent attribute values when
    | you retrieve or set them on model instances.
    |
    */

    // Mutator

    /**
     * @return void
     */
    public function setUserIdAttribute(int|null $value)
    {
        $this->attributes['user_id'] = $value === 0 ? self::GUEST_ID : $value;
    }

    /**
     * @return void
     */
    public function setLongUrlAttribute(string $value)
    {
        $this->attributes['long_url'] = rtrim($value, '/');
    }

    /**
     * @return void
     */
    public function setMetaTitleAttribute(string $value)
    {
        $this->attributes['meta_title'] = 'No Title';

        if (config('urlhub.web_title')) {
            $this->attributes['meta_title'] = $value;

            if (Str::startsWith($value, 'http')) {
                $this->attributes['meta_title'] = $this->getWebTitle($value);
            }
        }
    }

    // Accessor
    public function getShortUrlAttribute(): string
    {
        return url('/'.$this->attributes['keyword']);
    }

    /*
    |--------------------------------------------------------------------------
    | General Functions
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
            'is_custom'  => $request['custom_key'] ? 1 : 0,
            'ip'         => $request->ip(),
        ]);
    }

    /**
     * @param int|string|null $userId \Illuminate\Contracts\Auth\Guard::id()
     * @return bool \Illuminate\Database\Eloquent\Model::save()
     */
    public function duplicate(string $key, $userId, string $randomKey = null)
    {
        $randomKey = is_null($randomKey) ? $this->randomString() : $randomKey;
        $shortenedUrl = self::whereKeyword($key)->firstOrFail();

        $replicate = $shortenedUrl->replicate()->fill([
            'user_id'   => $userId,
            'keyword'   => $randomKey,
            'is_custom' => 0,
            'clicks'    => 0,
        ]);

        return $replicate->save();
    }

    public function urlKey(string $string): string
    {
        $length = config('urlhub.hash_length') * -1;

        // Step 1
        // Generate unique key from truncated long URL.
        $uniqueUrlKey = substr(preg_replace('/[^a-z0-9]/i', '', $string), $length);

        // Step 2
        // If the unique key in step 1 is not available (already used), then generate a
        // random string.
        $generatedRandomKey = self::whereKeyword($uniqueUrlKey)->first();
        while ($generatedRandomKey) {
            $uniqueUrlKey = $this->randomString();
            $generatedRandomKey = self::whereKeyword($uniqueUrlKey)->first();
        }

        return $uniqueUrlKey;
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
        $address = ! is_null($address) ? $address : (string) null;

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
     * or "{domain_name} - No Title" if not found.
     *
     * @throws \Exception
     */
    public function getWebTitle(string $url): string
    {
        $domain = $this->getDomain($url);
        $defaultWebTitle = $domain.' - No Title';

        try {
            $embeddedTitle = (new Embed)->get($url)->title;
            $webTitle = ! is_null($embeddedTitle) ? $embeddedTitle : $defaultWebTitle;
        } catch (\Exception) {
            $webTitle = $defaultWebTitle;
        }

        // @codeCoverageIgnoreStart
        // (new Embed())->get() datang dari module external dan membutuhkan koneksi
        // internet, jadi tidak perlu ditest.
        $stristr = stristr($domain, '.', true) === false ? $domain : stristr($domain, '.', true);
        if (stripos($webTitle, $stristr) === false) {
            return $domain.' | '.$webTitle;
        }
        // @codeCoverageIgnoreEnd

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
