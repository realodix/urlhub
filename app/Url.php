<?php

namespace App;

use App\Http\Traits\Hashidable;
use Embed\Embed;
use Hidehalo\Nanoid\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
            $this->attributes['meta_title'] = $this->get_remote_title($value);
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
        $alphabet = config('urlhub.hash_alphabet');
        $hash_length = (int) config('urlhub.hash_length');

        $keyword = $generateId->formatedId($alphabet, $hash_length);

        // If it is already used (not available), find the next available ending.
        // @codeCoverageIgnoreStart
        $link = self::whereKeyword($keyword)->first();
        while ($link) {
            $keyword = $generateId->formatedId($alphabet, $hash_length);
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
        $alphabet = strlen(config('urlhub.hash_alphabet'));
        $hash_length = (int) config('urlhub.hash_length');

        // If the hash size is filled with integers that do not match the rules
        // change the variable's value to 0.
        $hash_length = ! ($hash_length < 1) ? $hash_length : 0;

        if ($hash_length == 0) {
            return 0;
        } else {
            return pow($alphabet, $hash_length);
        }
    }

    /**
     * @return int
     */
    public function keyword_remaining()
    {
        $totalShortUrl = self::whereIsCustom(false)->count();

        if ($this->keyword_capacity() < $totalShortUrl) {
            return 0;
        }

        return $this->keyword_capacity() - $totalShortUrl;
    }

    /**
     * @return string
     */
    public function keyword_remaining_percent()
    {
        $capacity = $this->keyword_capacity();
        $remaining = $this->keyword_remaining();

        if ($capacity == 0) {
            return '0%';
        } else {
            if ((round(($remaining * 100) / $capacity, 2) == 100) && ($capacity != $remaining)) {
                return '99.99%';
            } else {
                return round(($remaining * 100) / $capacity).'%';
            }
        }
    }

    /**
     * This function returns a string: either the page title as defined in
     * HTML, or the string "No Title" if not found.
     *
     * @param string $url
     * @return string
     */
    public function get_remote_title($url)
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

        preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs);

        return $regs['domain'];
    }
}
