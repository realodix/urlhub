<?php

namespace App\Entities;
use Mekaeil\LaravelUserManagement\Entities\User as UserManagement;

class User extends UserManagement
{
    
    // protected $fillable = [
    //     'first_name',
    //     'last_name',
    //     'email',
    //     'mobile',
    //     'password',
    //     'status',           // 'pending','accepted','blocked' | DEFAULT: pending
    //     'email_verified',
    //     'mobile_verified',        
    // ];


    ////// !!! IMPORTANT !!!
    ////// WE ENCRYPT PASSWORD IN MODEL YOU CAN OVERWRITE IT AND REMOVE IT
    // public function setPasswordAttribute($password)
    // {
    //     $this->attributes['password'] = bcrypt($password);
    // }    

}