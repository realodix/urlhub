<?php

namespace App;

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

    public function getLongUrlLimitAttribute()
    {
        return url_normalize(url_limit($this->long_url));
    }
}
