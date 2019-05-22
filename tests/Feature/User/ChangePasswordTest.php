<?php

namespace Tests\Feature\User;

use Illuminate\Support\Facades\Hash;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use MigrateFreshSeedOnce;

    protected function cPwdGetRoute($value)
    {
        return route('user.change-password', $value);
    }

    protected function cPwdPostRoute($value)
    {
        return route('user.change-password.post', \Hashids::connection(\App\User::class)->encode($value));
    }

    /** @test */
    public function change_password_with_correct_credentials()
    {
        $this->loginAsAdmin();

        $response = $this->from($this->cPwdGetRoute($this->admin()->name))
                         ->post($this->cPwdPostRoute($this->admin()->id), [
                            'current-password'          => $this->adminPassword(),
                            'new-password'              => 'new-awesome-password',
                            'new-password_confirmation' => 'new-awesome-password',
                         ]);

        $response->assertRedirect($this->cPwdGetRoute($this->admin()->name));
        $this->assertTrue(Hash::check('new-awesome-password', $this->admin()->fresh()->password));
        $response->assertSessionHas(['flash_success']);
    }

    /** @test */
    public function admin_can_access_a_user_change_password_page()
    {
        $this->loginAsAdmin();

        $response = $this->get($this->cPwdGetRoute($this->user()->name));
        $response->assertStatus(200);
    }

    /** @test */
    public function user_cant_access_a_admin_change_password_page()
    {
        $this->loginAsUser();

        $response = $this->get($this->cPwdGetRoute($this->admin()->name));
        $response->assertStatus(403);
    }
}
