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

        $this->createAdmin();
        $this->createUser();
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
        return User::whereName('user')->first();
    }

    protected function userPassword()
    {
        return 'user';
    }

    protected function loginAsUser()
    {
        return $this->actingAs($this->user());
    }

    public function createAdmin()
    {
        $now = now();

        $attributes = [
            'name'       => 'admin',
            'email'      => 'admin@urlhub.test',
            'password'   => bcrypt('admin'),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $admin = factory(User::class)->create($attributes);
        $admin->assignRole($this->getAdminRole());

        return $admin;
    }

    public function createUser()
    {
        $now = now();

        $attributes = [
            'name'       => 'user',
            'email'      => 'user@urlhub.test',
            'password'   => bcrypt('user'),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $user = factory(User::class)->create($attributes);

        return  $user;
    }

    public function getAdminRole()
    {
        // create permissions
        Permission::create(['name' => 'admin']);

        // create roles and assign created permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // $adminRole = factory(Role::class)->create(['name' => config('access.users.admin_role')]);
        // $adminRole->givePermissionTo(factory(Permission::class)->create(['name' => 'view backend']));
        return $adminRole;
    }
}
