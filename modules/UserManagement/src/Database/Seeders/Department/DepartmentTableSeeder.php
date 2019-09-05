<?php

use Mekaeil\LaravelUserManagement\seeders\Department\MasterDepartmentTableSeeder;

class DepartmentTableSeeder extends MasterDepartmentTableSeeder
{
    protected $departments = [
        [
            'title'     => "Clients",
            'parent'    => '',    
        ],
        
    ];

    
}
