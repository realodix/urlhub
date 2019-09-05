<?php

namespace UrlHub\UserManagement\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile',
        'password',
        'status',           // 'pending','accepted','blocked' | DEFAULT: pending
        'email_verified',
        'mobile_verified',
    ];


    /**
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config("laravel_user_management.users_table"));
    }

    public function departments()
    {
        $table  = config("laravel_user_management.user_department_user_table");

        return $this->belongsToMany(
            Department::class,
            $table,
            'user_id',
            'department_id'
        );
    }
}
