<?php

namespace App;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Traits\Hashidable;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravolt\Avatar\Facade as Avatar;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Hashidable;
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function url()
    {
        return $this->hasMany('App\Url');
    }

    /**
     * Get User avatar.
     */
    public function getAvatarAttribute()
    {
        // Check if Gravatar has an avatar for the given email address
        if (Gravatar::exists($this->email) == true) {
            // Get the gravatar url
            return Gravatar::get($this->email);
        }

        // Create unique avatar based on their email
        return Avatar::create(title_case($this->email))->toBase64();
    }
}
