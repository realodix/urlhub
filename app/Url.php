<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'long_url',
        'short_url',
        'users_id',
        'ip',
    ];
}
