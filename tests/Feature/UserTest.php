<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /*
     |
     | Change User Password
     |
     */

    protected function cPwdGetRoute($value)
    {
        return route('user.change-password', $value);
    }

    protected function cPwdPostRoute($value)
    {
        return route('user.change-password.post', \Hashids::connection(\App\User::class)->encode($value));
    }

    /** @test */
    public function an_admin_can_access_a_user_change_password_page()
    {
        $user = $this->loginAsAdmin();

        $response = $this->get($this->cPwdGetRoute($user->name));
        $response->assertStatus(200);
    }

    /** @test */
    public function change_password_with_correct_credentials()
    {
        $user = $this->loginAsAdmin();

        $response = $this->from($this->cPwdGetRoute($user->name))
                         ->post($this->cPwdPostRoute($user->id), [
                            'current-password'          => 'old-password',
                            'new-password'              => 'new-awesome-password',
                            'new-password_confirmation' => 'new-awesome-password',
                         ]);

        $response->assertRedirect($this->cPwdGetRoute($user->name));
        $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));
        $response->assertSessionHas(['flash_success']);
    }

    /*
     |
     |
     |
     */

    /**
     * Create the admin role or return it if it already exists.
     *
     * @return mixed
     */
    protected function getAdminRole()
    {
        if ($role = Role::whereName('admin')->first()) {
            return $role;
        }

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::create(['name' => 'admin']));

        return $adminRole;
    }

    /**
     * Create an administrator.
     *
     * @param array $attributes
     *
     * @return mixed
     */
    protected function createAdmin(array $attributes = [])
    {
        $adminRole = $this->getAdminRole();

        $admin = factory(User::class)->create($attributes);
        $admin->assignRole($adminRole);

        return $admin;
    }

    /**
     * Login the given administrator or create the first if none supplied.
     *
     * @param bool $admin
     *
     * @return bool|mixed
     */
    protected function loginAsAdmin($admin = false)
    {
        if (! $admin) {
            $admin = $this->createAdmin([
                'password' => Hash::make('old-password'),
            ]);
        }

        $this->actingAs($admin);

        return $admin;
    }
}
