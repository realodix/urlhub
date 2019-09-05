<?php

namespace App\Entities;

use UrlHub\UserManagement\Entities\Department as UserManagementDepartment;

class Department extends UserManagementDepartment
{
    protected $fillable = [
        'title',
        'slug',
        'parent_id',
    ];
}
