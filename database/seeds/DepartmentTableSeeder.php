<?php

use UrlHub\UserManagement\seeders\Department\MasterDepartmentTableSeeder;

class DepartmentTableSeeder extends MasterDepartmentTableSeeder
{
    protected $departments = [
        [
            'title'     => 'Clients',
            'parent'    => '',
        ],

    ];
}
