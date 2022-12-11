<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Visit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
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

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo(Url::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Other Functions
    |--------------------------------------------------------------------------
    */

    /**
     * @return string
     */
    public function getIp()
    {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = Request::ip();
        }

        return Helper::anonymizeIp($ip);
    }
}
