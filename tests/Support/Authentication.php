<?php

namespace Tests\Support;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait Authentication
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create([
            'id'       => 1,
            'password' => bcrypt($this->adminPass()),
        ]);
        $admin->assignRole($this->getAdminRole());
    }

    protected function admin()
    {
        return User::whereId(1)->first();
    }

    protected function adminPass()
    {
        return 'admin';
    }

    protected function loginAsAdmin()
    {
        return $this->actingAs($this->admin());
    }

    protected function nonAdmin()
    {
        return User::factory()->create();
    }

    protected function loginAsNonAdmin()
    {
        return $this->actingAs($this->nonAdmin());
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
