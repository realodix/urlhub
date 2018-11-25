<?php

namespace App;

use App\Http\Traits\Hashidable;
use Facades\App\Helpers\UrlHlp;
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

    public function user()
    {
        return $this->belongsTo('App\User')->withDefault([
            'name' => 'Guest',
        ]);
    }

    public function setLongUrlAttribute($value)
    {
        $this->attributes['long_url'] = rtrim($value, '/');
    }

    public function setMetaTitleAttribute($value)
    {
        $this->attributes['meta_title'] = UrlHlp::getTitle($value);
    }

    public function getShortUrlAttribute()
    {
        return url('/'.$this->attributes['url_key']);
    }
}
