<?php

namespace App;

use Facades\App\Helpers\UrlHlp;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class Url extends Model
{
    protected $fillable = [
        'user_id',
        'long_url',
        'meta_title',
        'short_url',
        'short_url_custom',
        'views',
        'ip',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function setMetaTitleAttribute($value)
    {
        $this->attributes['meta_title'] = UrlHlp::getTitle($value);
    }

    public function getIdAttribute($value)
    {
        return Hashids::encode($value);
    }

    public function getViewByIdAttribute()
    {
        if ($this->attributes['short_url_custom'] == null) {
            return $this->attributes['short_url'];
        }

        return $this->attributes['short_url_custom'];
    }
}
