<?php

namespace Tests\Feature\User;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    protected function getRoute($value)
    {
        return route('user.change-password', $value);
    }

    protected function postRoute($value)
    {
        return route('user.change-password.post', \Hashids::connection(\App\User::class)->encode($value));
    }

    /** @test */
    public function change_password_with_correct_credentials()
    {
        $this->loginAsAdmin();

        $response = $this->from($this->getRoute($this->admin()->name))
                         ->post($this->postRoute($this->admin()->id), [
                            'current-password'          => $this->adminPassword(),
                            'new-password'              => 'new-awesome-password',
                            'new-password_confirmation' => 'new-awesome-password',
                         ]);

        $response->assertRedirect($this->getRoute($this->admin()->name));
        $this->assertTrue(Hash::check('new-awesome-password', $this->admin()->fresh()->password));
        $response->assertSessionHas(['flash_success']);
    }

    /** @test */
    public function admin_can_change_the_password_of_all_users()
    {
        $this->loginAsAdmin();

        $user = $this->user();

        $response = $this->from($this->getRoute($user->name))
                         ->post($this->postRoute($user->id), [
                            'current-password'          => $this->adminPassword(),
                            'new-password'              => 'new-awesome-password',
                            'new-password_confirmation' => 'new-awesome-password',
                         ]);

        $response->assertRedirect($this->getRoute($user->name));
        $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));
        $response->assertSessionHas(['flash_success']);
    }

    /**
     * Current password is wrong.
     *
     * @test
     */
    public function verifying_a_password_against_a_hash()
    {
        $this->loginAsNonAdmin();

        $user = $this->user();

        $response = $this->from($this->getRoute($user->name))
                         ->post($this->postRoute($user->id), [
                            'current-password'          => 'laravel',
                            'new-password'              => 'new-awesome-password',
                            'new-password_confirmation' => 'new-awesome-password',
                         ]);

        $response->assertRedirect($this->getRoute($user->name));
        $this->assertFalse(Hash::check('new-awesome-password', $user->fresh()->password));
        $response->assertSessionHas('flash_error');
    }

    /** @test */
    public function new_password_validate_required()
    {
        $this->loginAsNonAdmin();

        $user = $this->user();

        $response = $this->from($this->getRoute($user->name))
                         ->post($this->postRoute($user->id), [
                            'current-password'          => $this->nonAdminPassword(),
                            'new-password'              => '',
                            'new-password_confirmation' => '',
                         ]);

        $response->assertRedirect($this->getRoute($user->name));
        $this->assertFalse(Hash::check('new-awesome-password', $user->fresh()->password));
        $response->assertSessionHasErrors('new-password');
    }

    /** @test */
    public function new_password_validate_string()
    {
        $this->loginAsNonAdmin();

        $user = $this->user();

        $response = $this->from($this->getRoute($user->name))
                         ->post($this->postRoute($user->id), [
                            'current-password'          => $this->nonAdminPassword(),
                            'new-password'              => null,
                            'new-password_confirmation' => null,
                         ]);

        $response->assertRedirect($this->getRoute($user->name));
        $this->assertFalse(Hash::check('new-awesome-password', $user->fresh()->password));
        $response->assertSessionHasErrors('new-password');
    }

    /** @test */
    public function new_password_validate_min_lenght()
    {
        $this->loginAsNonAdmin();

        $user = $this->user();

        $response = $this->from($this->getRoute($user->name))
                         ->post($this->postRoute($user->id), [
                            'current-password'          => $this->nonAdminPassword(),
                            'new-password'              => str_repeat('a', 5),
                            'new-password_confirmation' => str_repeat('a', 5),
                         ]);

        $response->assertRedirect($this->getRoute($user->name));
        $this->assertFalse(Hash::check('new-awesome-password', $user->fresh()->password));
        $response->assertSessionHasErrors('new-password');
    }

    /** @test */
    public function new_password_validate_confirmed()
    {
        $this->loginAsNonAdmin();

        $user = $this->user();

        $response = $this->from($this->getRoute($user->name))
                         ->post($this->postRoute($user->id), [
                            'current-password'          => $this->nonAdminPassword(),
                            'new-password'              => 'new-awesome-password',
                            'new-password_confirmation' => 'new-awesome-pass',
                         ]);

        $response->assertRedirect($this->getRoute($user->name));
        $this->assertFalse(Hash::check('new-awesome-password', $user->fresh()->password));
        $response->assertSessionHasErrors('new-password');
    }
}
