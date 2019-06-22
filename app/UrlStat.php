<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlStat extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url_key',
        'click',
        'referer',
        'ip',
    ];

    // Relations
    public function url()
    {
        return $this->belongsTo('App\Url', 'url_key', 'url_key');
    }
}
