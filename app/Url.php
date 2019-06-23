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

        $this->attributes['meta_title'] = $UrlSrvc->getTitle($value);
    }

    // Accessor
    public function getShortUrlAttribute()
    {
        return url('/'.$this->attributes['url_key']);
    }

    public function getClicksAttribute()
    {
        $clicks = self::UrlStat()->whereUrlId($this->attributes['id'])->sum('click');

        return $clicks;
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
        return self::UrlStat()->sum('click');
    }

    /**
     * @param int $id
     */
    public function totalClicksById($id = null):int
    {
        $url = self::whereUserId($id)->firstOrFail();

        return self::UrlStat()->whereUrlId($url->id)->sum('click');
    }
}
