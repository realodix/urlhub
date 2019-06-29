<?php

namespace App;

use App\Http\Traits\Hashidable;
use App\Services\UrlService;
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
        $UrlSrvc = new UrlService();

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

    /**
     * Gets the title of page from its url.
     *
     * @param string $url
     * @return string
     */
    public function getTitle($url)
    {
        if ($title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', @file_get_contents($url), $matches)) {
            return $matches[1];
        } elseif ($domain = $this->getDomain($url)) {
            // @codeCoverageIgnoreStart
            return title_case($domain).' - '.__('No Title'); // @codeCoverageIgnoreEnd
        } else {
            return __('No Title');
        }
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
