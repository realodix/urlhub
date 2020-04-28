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
        'country',
        'country_full',
    ];

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Relationships
    |--------------------------------------------------------------------------
    | Database tables are often related to one another. Eloquent relationships
    | are defined as methods on Eloquent model classes.
    */

    public function url()
    {
        return $this->belongsTo('App\Url');
    }

    /*
    |--------------------------------------------------------------------------
    | UrlHub Functions
    |--------------------------------------------------------------------------
    */
}
