<?php

namespace App;

use App\Http\Traits\Hashidable;
use Embed\Embed;
use Hidehalo\Nanoid\Client;
use Illuminate\Database\Eloquent\Model;

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
        'url_key',
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
        'is_custom' => 'boolean',
    ];

    // Relations
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
        $this->attributes['meta_title'] = $this->getTitle($value);
    }

    // Accessor
    public function getShortUrlAttribute()
    {
        return url('/'.$this->attributes['url_key']);
    }

    /*
     |
     |
     */

    public function totalShortUrl()
    {
        return self::count('url_key');
    }

    /**
     * @param int $id
     */
    public function totalShortUrlById($id = null)
    {
        return self::whereUserId($id)->count('url_key');
    }

    public function totalClicks():int
    {
        return self::sum('clicks');
    }

    /**
     * @param int $id
     */
    public function totalClicksById($id = null):int
    {
        return self::whereUserId($id)->sum('clicks');
    }

    /*
     |
     |
     */

    /**
     * Generate an unique short URL using Nanoid.
     *
     * @return string
     */
    public function key_generator()
    {
        $generateId = new Client();
        $alphabet = config('urlhub.hash_alphabet');
        $size1 = (int) config('urlhub.hash_size_1');
        $size2 = (int) config('urlhub.hash_size_2');

        // @codeCoverageIgnoreStart
        if (($size1 == $size2) || $size2 == 0) {
            $size2 = $size1;
        }
        // @codeCoverageIgnoreEnd

        $urlKey = $generateId->formatedId($alphabet, $size1);

        // If it is already used (not available), find the next available ending.
        // @codeCoverageIgnoreStart
        $link = self::whereUrlKey($urlKey)->first();

        while ($link) {
            $urlKey = $generateId->formatedId($alphabet, $size2);
            $link = self::whereUrlKey($urlKey)->first();
        }
        // @codeCoverageIgnoreEnd

        return $urlKey;
    }

    /**
     * @return int
     */
    public function url_key_capacity()
    {
        $alphabet = strlen(config('urlhub.hash_alphabet'));
        $size1 = (int) config('urlhub.hash_size_1');
        $size2 = (int) config('urlhub.hash_size_2');

        // If the hash size is filled with integers that do not match the rules
        // change the variable's value to 0.
        $size1 = ! ($size1 < 1) ? $size1 : 0;
        $size2 = ! ($size2 < 0) ? $size2 : 0;

        if ($size1 == 0 || ($size1 == 0 && $size2 == 0)) {
            return 0;
        } elseif ($size1 == $size2 || $size2 == 0) {
            return pow($alphabet, $size1);
        } else {
            return pow($alphabet, $size1) + pow($alphabet, $size2);
        }
    }

    /**
     * @return int
     */
    public function url_key_remaining()
    {
        $totalShortUrl = self::whereIsCustom(false)->count();

        if ($this->url_key_capacity() < $totalShortUrl) {
            return 0;
        }

        return $this->url_key_capacity() - $totalShortUrl;
    }

    /**
     * Gets the title of page from its url.
     *
     * @param string $url
     * @return string
     */
    public function getTitle($url)
    {
        try {
            $embed = Embed::create($url);
            $title = $embed->title;
        } catch (\Exception $e) {
            $title = 'No Title';
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
        // https://stackoverflow.com/a/399316
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
    }
}
