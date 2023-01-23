<?php

namespace Tests\Support;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait Auth
{
    protected $adminRole = 'admin';

    protected $adminPass = 'admin';

    protected function setUp(): void
    {
        parent::setUp();

        // create permissions
        Permission::create(['name' => $this->adminRole]);

        // create roles and assign created permissions
        $adminRole = Role::create(['name' => $this->adminRole]);
        $adminRole->givePermissionTo(Permission::all());
    }

    protected function adminUser()
    {
        $admin = User::factory()->create([
            'password' => bcrypt($this->adminPass),
        ]);
        $admin->assignRole($this->adminRole);

        return $admin;
    }

    protected function normalUser()
    {
        return User::factory()->create();
    }
}
