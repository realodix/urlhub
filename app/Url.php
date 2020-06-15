<?php

namespace App;

use App\Http\Traits\Hashidable;
use Embed\Embed;
use GeoIp2\Database\Reader;
use Hidehalo\Nanoid\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Url\Url as SpatieUrl;

class Url extends Model
{
    use Hashidable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
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
     * @var array
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
        return $this->belongsTo('App\User')->withDefault([
            'name' => 'Guest',
        ]);
    }

    public function urlStat()
    {
        return $this->hasMany('App\UrlStat');
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
            $this->attributes['meta_title'] = $this->getRemoteTitle($value);
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
    | UrlHub Functions
    |--------------------------------------------------------------------------
    */

    public function totalShortUrl()
    {
        return self::count('keyword');
    }

    /**
     * @param int $id
     */
    public function totalShortUrlById($id = null)
    {
        return self::whereUserId($id)->count('keyword');
    }

    public function totalClicks(): int
    {
        return self::sum('clicks');
    }

    /**
     * @param int $id
     */
    public function totalClicksById($id = null): int
    {
        return self::whereUserId($id)->sum('clicks');
    }

    /**
     * Generate an unique short URL using Nanoid.
     *
     * @return string
     */
    public function key_generator()
    {
        $generateId = new Client();
        $alphabet = uHub('hash_alphabet');
        $hashLength = (int) uHub('hash_length');

        $keyword = $generateId->formatedId($alphabet, $hashLength);

        // If it is already used (not available), find the next available ending.
        // @codeCoverageIgnoreStart
        $link = self::whereKeyword($keyword)->first();
        while ($link) {
            $keyword = $generateId->formatedId($alphabet, $hashLength);
            $link = self::whereKeyword($keyword)->first();
        }
        // @codeCoverageIgnoreEnd

        return $keyword;
    }

    /**
     * @return int
     */
    public function keyword_capacity()
    {
        $alphabet = strlen(uHub('hash_alphabet'));
        $hashLength = (int) uHub('hash_length');

        // If the value is smaller than 1, then change the value to 0.
        $hashLength = ! ($hashLength < 1) ? $hashLength : 0;

        if ($hashLength == 0) {
            return 0;
        }

        return pow($alphabet, $hashLength);
    }

    /**
     * @return int
     */
    public function keyword_remaining()
    {
        $randomKeyword = self::whereIsCustom(false)->count();
        $customKeyword = self::whereIsCustom(true)
                               ->whereRaw('LENGTH(keyword) = ?', [uHub('hash_length')])
                               ->where('keyword', 'NOT LIKE', '[_]')
                               ->count();

        $usedKeyword = $randomKeyword + $customKeyword;

        if ($this->keyword_capacity() < $usedKeyword) {
            return 0;
        }

        return $this->keyword_capacity() - $usedKeyword;
    }

    /**
     * @return string
     */
    public function keyword_remaining_percent()
    {
        $capacity = $this->keyword_capacity();
        $remaining = $this->keyword_remaining();

        if ((round(($remaining * 100) / $capacity) == 100) && ($capacity != $remaining)) {
            return '99.99%';
        } else {
            return round(($remaining * 100) / $capacity).'%';
        }
    }

    /**
     * This function returns a string: either the page title as defined in
     * HTML, or "{domain_name} - No Title" if not found.
     *
     * @param string $url
     * @return string
     */
    public function getRemoteTitle($url)
    {
        try {
            $embed = Embed::create($url);
            $title = $embed->title;
        } catch (\Exception $e) {
            $title = $this->getDomain($url).' - No Title';
        }

        return $title;
    }

    /**
     * Get Domain from external url.
     *
     * Extract the domain name using the classic parse_url() and then look
     * for a valid domain without any subdomain (www being a subdomain).
     * Won't work on things like 'localhost'.
     *
     * @param string $url
     * @return mixed
     */
    public function getDomain($url)
    {
        $url = SpatieUrl::fromString($url);

        return urlRemoveScheme($url->getHost());
    }

    /**
     * IP Address to Identify Geolocation Information. If it fails, because
     * GeoLite2 doesn't know the IP country, we will set it to Unknown.
     */
    public function ipToCountry($ip)
    {
        try {
            // @codeCoverageIgnoreStart
            $reader = new Reader(database_path().'/dbip-country-lite-2020-06.mmdb');
            $record = $reader->country($ip);
            $countryCode = $record->country->isoCode;
            $countryName = $record->country->name;

            return compact('countryCode', 'countryName');
            // @codeCoverageIgnoreEnd
        } catch (\Exception $e) {
            $countryCode = 'N/A';
            $countryName = 'Unknown';

            return compact('countryCode', 'countryName');
        }
    }
}
