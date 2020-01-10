<?php

namespace Tests\Support;

use App\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait Authentication
{
    public function setUp(): void
    {
        parent::setUp();

        $admin = factory(User::class)->create([
            'id'       => 1,
            'password' => bcrypt($this->adminPassword()),
        ]);
        $admin->assignRole($this->getAdminRole());
    }

    protected function admin()
    {
        return User::whereId(1)->first();
    }

    protected function adminPassword()
    {
        return 'admin';
    }

    protected function loginAsAdmin()
    {
        return $this->actingAs($this->admin());
    }

    protected function user()
    {
        return factory(User::class)->create();
    }

    protected function loginAsUser()
    {
        return $this->actingAs($this->user());
    }

    public function getAdminRole()
    {
        // create permissions
        Permission::create(['name' => 'admin']);

        // create roles and assign created permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        return $adminRole;
    }
}
