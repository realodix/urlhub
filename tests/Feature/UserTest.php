<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class UserTest extends TestCase
{
    use MigrateFreshSeedOnce;

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
    public function admin_can_access_a_user_change_password_page()
    {
        $user = $this->loginAsAdmin();

        $response = $this->get($this->cPwdGetRoute($user->name));
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_change_password_with_correct_credentials()
    {
        $user = $this->loginAsAdmin();

        $response = $this->from($this->cPwdGetRoute($user->name))
                         ->post($this->cPwdPostRoute($user->id), [
                            'current-password'          => $this->adminPassword(),
                            'new-password'              => 'new-awesome-password',
                            'new-password_confirmation' => 'new-awesome-password',
                         ]);

        $response->assertRedirect($this->cPwdGetRoute($user->name));
        $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));
        $response->assertSessionHas(['flash_success']);
    }

    /** @test */
    public function user_can_change_password_with_correct_credentials()
    {
        $user = $this->loginAsUser();

        $response = $this->from($this->cPwdGetRoute($user->name))
                         ->post($this->cPwdPostRoute($user->id), [
                            'current-password'          => $this->userPassword(),
                            'new-password'              => 'new-awesome-password',
                            'new-password_confirmation' => 'new-awesome-password',
                         ]);

        $response->assertRedirect($this->cPwdGetRoute($user->name));
        $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));
        $response->assertSessionHas(['flash_success']);
    }
}
