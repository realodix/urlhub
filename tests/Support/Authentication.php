<?php

namespace Tests\Support;

use App\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait Authentication
{
    public function setUp():void
    {
        parent::setUp();

        $now = now();

        $admin = factory(User::class)->create([
            'id'         => 1,
            'name'       => 'admin',
            'email'      => 'admin@urlhub.test',
            'password'   => bcrypt('admin'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $admin->assignRole($this->getAdminRole());
    }

    protected function admin()
    {
        return User::whereName('admin')->first();
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
        $user = factory(User::class)->create([
            'password'   => bcrypt($this->userPassword()),
        ]);

        return $user;
    }

    protected function userPassword()
    {
        return 'user';
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
