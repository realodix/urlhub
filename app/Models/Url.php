<?php

namespace App\Models;

use App\Http\Traits\Hashidable;
use App\Services\KeyService;
use Embed\Embed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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
     * @param  string  $key
     * @param  int  $authId
     */
    public function duplicate($key, $authId)
    {
        $keySrvc = new KeyService;
        $randomKey = $keySrvc->randomString();
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
}
