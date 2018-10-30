<?php

namespace App;

use Facades\App\Helpers\UrlHlp;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class Url extends Model
{
    protected $fillable = [
        'user_id',
        'url_key',
        'is_custom',
        'long_url',
        'meta_title',
        'views',
        'ip',
    ];

    public function user()
    {
        return $this->belongsTo('App\User')->withDefault([
            'name' => 'Guest',
        ]);
    }

    public function setMetaTitleAttribute($value)
    {
        $this->attributes['meta_title'] = UrlHlp::getTitle($value);
    }

    public function getIdAttribute($value)
    {
        return Hashids::encode($value);
    }

    public function getShortUrlAttribute()
    {
        return url('/'.$this->attributes['url_key']);
    }
}
