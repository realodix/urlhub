<?php

use Illuminate\Database\Seeder;
use UrlHub\UserManagement\seeders\Permission\MasterRoleTableSeeder;

class RoleTableSeeder extends MasterRoleTableSeeder
{
    protected $roles = [
        [
            'name'          => "Admin",
            'title'         => "Administrator",
            'guard_name'    => "web",
            'description'   => "This role will assign to Administrator",
        ],
        [
            'name'          => "User",
            'title'         => "User",
            'guard_name'    => "web",
            'description'   => "This role will assign to user.",
        ],

    ];
}
