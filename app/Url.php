<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'users_id',
        'long_url',
        'long_url_title',
        'short_url',
        'ip',
    ];
}
