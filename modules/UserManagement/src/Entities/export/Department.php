<?php

namespace App\Entities;
use Mekaeil\LaravelUserManagement\Entities\Department as UserManagementDepartment;

class Department extends UserManagementDepartment
{
    protected $fillable = [
        'title',
        'slug',
        'parent_id',
    ];

}
