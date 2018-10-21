<?php

namespace App;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Creativeorange\Gravatar\Gravatar;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravolt\Avatar\Avatar;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
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
     * Get User avatar
     */
    public function getAvatarAttribute()
    {
        $avatar = new Avatar();
        $gravatar = new Gravatar();

        if ($gravatar->exists($this->email) == true) {
            return $gravatar->get($this->email);
        }

        return $avatar->create(title_case($this->email))->toBase64();
    }
}
