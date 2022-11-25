<?php

namespace Tests\Support;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait Authentication
{
    private $adminRole = 'admin';

    protected $adminPass = 'admin';

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create([
            'password' => bcrypt($this->adminPass),
        ]);
        $admin->assignRole($this->getAdminRole());
    }

    protected function admin()
    {
        return User::role($this->adminRole)->first();
    }

    protected function nonAdmin()
    {
        return User::factory()->create();
    }

    private function getAdminRole()
    {
        // create permissions
        Permission::create(['name' => $this->adminRole]);

        // create roles and assign created permissions
        $adminRole = Role::create(['name' => $this->adminRole]);
        $adminRole->givePermissionTo(Permission::all());

        return $adminRole;
    }
}
