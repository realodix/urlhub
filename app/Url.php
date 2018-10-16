<?php

namespace App;

use Facades\App\Helpers\UrlHlp;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'user_id',
        'long_url',
        'long_url_title',
        'short_url',
        'short_url_custom',
        'views',
        'ip',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function setLongUrlTitleAttribute($value)
    {
        $this->attributes['long_url_title'] = UrlHlp::url_get_title($value);
    }
}
