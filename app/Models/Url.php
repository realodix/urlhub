<?php

namespace App\Models;

use App\Http\Traits\Hashidable;
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
     *@var array<string, string>
     */
    protected $casts = [
        'user_id'   => 'int',
        'is_custom' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Relationships
    |--------------------------------------------------------------------------
    | Database tables are often related to one another. Eloquent relationships
    | are defined as methods on Eloquent model classes.
    */

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault([
            'name' => 'Guest',
        ]);
    }

    public function visit()
    {
        return $this->hasMany('App\Models\Visit');
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
    public function setUserIdAttribute($value)
    {
        if ($value == 0) {
            $this->attributes['user_id'] = null;
        } else {
            $this->attributes['user_id'] = $value;
        }
    }

    public function setLongUrlAttribute($value)
    {
        $this->attributes['long_url'] = rtrim($value, '/');
    }

    public function setMetaTitleAttribute($value)
    {
        if (Str::startsWith($value, 'http')) {
            $this->attributes['meta_title'] = $this->getWebTitle($value);
        } else {
            $this->attributes['meta_title'] = $value;
        }
    }

    // Accessor
    public function getShortUrlAttribute()
    {
        return url('/'.$this->attributes['keyword']);
    }

    /*
    |--------------------------------------------------------------------------
    | General Functions
    |--------------------------------------------------------------------------
    */

    /**
     * @param  array|string  $request
     * @param  int  $authId
     */
    public function shortenUrl($request, $authId)
    {
        $key = $request['custom_key'] ?? $this->urlKey($request['long_url']);

        return Url::create([
            'user_id'    => $authId,
            'long_url'   => $request['long_url'],
            'meta_title' => $request['long_url'],
            'keyword'    => $key,
            'is_custom'  => $request['custom_key'] ? 1 : 0,
            'ip'         => request()->ip(),
        ]);
    }

    /**
     * @param  string  $key
     * @param  int  $authId
     */
    public function duplicate($key, $authId)
    {
        $randomKey = $this->randomString();
        $shortenedUrl = self::whereKeyword($key)->firstOrFail();

        $replicate = $shortenedUrl->replicate()->fill([
            'user_id'   => $authId,
            'keyword'   => $randomKey,
            'is_custom' => 0,
            'clicks'    => 0,
        ]);
        $replicate->save();

        return $replicate;
    }

    /**
     * @param  string  $string
     */
    public function urlKey(string $string)
    {
        $length = config('urlhub.hash_length') * -1;

        // Step 1
        // Generate unique key from truncated long URL.
        $uniqueUrlKey = substr(preg_replace('/[^a-z0-9]/i', '', $string), $length);

        // Step 2
        // If the unique key is not available (already in the database) , then generate a
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
     * customKey must be similar to randomKey, such as having the same character length.
     */
    public function keyUsed()
    {
        $hashLength = uHub('hash_length');
        $regexPattern = '['.uHub('hash_char').']{'.$hashLength.'}';

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
     * Calculates the maximum number of unique random strings that can be supplied.
     *
     * @return int
     */
    public function keyCapacity()
    {
        $alphabet = strlen(uHub('hash_char'));
        $length = uHub('hash_length');

        // for testing purposes only
        // tests\Unit\Middleware\UrlHubLinkCheckerTest.php
        if ($length == 0) {
            return 0;
        }

        return pow($alphabet, $length);
    }

    /**
     * Counts unique random strings that can be supplied.
     *
     * @return int
     */
    public function keyRemaining()
    {
        $keyCapacity = $this->keyCapacity();
        $keyUsed = $this->keyUsed();

        return max($keyCapacity - $keyUsed, 0);
    }

    public function keyRemainingInPercent()
    {
        $capacity = $this->keyCapacity();
        $used = $this->keyUsed();
        $remaining = $this->keyRemaining();

        $result = round(($remaining / $capacity) * 100, 2);

        if (($result == 0) && ($capacity <= $used)) {
            return '0%';
        } elseif (($result == 0) && ($capacity > $used)) {
            return '0.01%';
        } elseif (($result == 100) && ($capacity != $remaining)) {
            return '99.99%';
        }

        return $result.'%';
    }

    /**
     * @param  int  $id
     */
    public function urlCount($id = null)
    {
        return self::whereUserId($id)->count('keyword');
    }

    public function totalUrl()
    {
        return self::count('keyword');
    }

    /**
     * @param  int  $id
     */
    public function clickCount($id = null): int
    {
        return self::whereUserId($id)->sum('clicks');
    }

    public function totalClick(): int
    {
        return self::sum('clicks');
    }

    /**
     * Anonymize an IPv4 or IPv6 address.
     *
     * @param  string  $address
     * @return string
     */
    public static function anonymizeIp($address)
    {
        if (uHub('anonymize_ip_addr') == false) {
            return $address;
        }

        return IPUtils::anonymize($address);
    }

    /**
     * Get Domain from external url.
     *
     * Extract the domain name using the classic parse_url() and then look for
     * a valid domain without any subdomain (www being a subdomain). Won't
     * work on things like 'localhost'.
     *
     * @param  string  $url
     * @return string
     */
    public function getDomain(string $url)
    {
        $url = SpatieUrl::fromString($url);

        return urlSanitize($url->getHost());
    }

    /**
     * This function returns a string: either the page title as defined in
     * HTML, or "{domain_name} - No Title" if not found.
     *
     * @param  string  $url
     * @return string
     */
    public function getWebTitle(string $url)
    {
        $domain = $this->getDomain($url);

        try {
            $webTitle = (new Embed())->get($url)->title;
        } catch (\Exception $e) {
            $webTitle = $domain.' - No Title';
        }

        if (stripos($webTitle, stristr($domain, '.', true)) === false) {
            return $domain.' | '.$webTitle;
        }

        return $webTitle;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function randomString()
    {
        $alphabet = uHub('hash_char');
        $length = uHub('hash_length');
        $factory = new RandomLibFactory();

        return $factory->getMediumStrengthGenerator()->generateString($length, $alphabet);
    }
}
