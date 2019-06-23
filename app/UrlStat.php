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
        'url_id',
        'referer',
        'ip',
        'device',
        'platform',
        'platform_version',
        'browser',
        'browser_version',
    ];

    // Relations
    public function url()
    {
        return $this->belongsTo('App\Url');
    }
}
