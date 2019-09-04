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
    public function users_can_access_their_own_change_password_page()
    {
        $this->loginAsAdmin();

        $response = $this->get($this->getRoute($this->admin()->name));
        $response->assertOk();
    }

    /** @test */
    public function admin_can_access_other_users_change_password_pages()
    {
        $this->loginAsAdmin();

        $response = $this->get($this->getRoute($this->user()->name));
        $response->assertOk();
    }

    /** @test */
    public function non_admin_cant_access_other_users_change_password_pages()
    {
        $this->loginAsUser();

        $response = $this->get($this->getRoute($this->admin()->name));
        $response->assertForbidden();
    }

    /** @test */
    public function change_password_with_correct_credentials()
    {
        $this->loginAsAdmin();

        $user = $this->admin();

        $response = $this->from($this->getRoute($user->name))
                         ->post($this->postRoute($user->id), [
                            'current-password'          => $this->adminPassword(),
                            'new-password'              => 'new-awesome-password',
                            'new-password_confirmation' => 'new-awesome-password',
                         ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHas('flash_success');

        $this->assertTrue(
            Hash::check(
                'new-awesome-password',
                $user->fresh()->password
            )
        );
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

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHas('flash_success');

        $this->assertTrue(
            Hash::check(
                'new-awesome-password',
                $user->fresh()->password
            )
        );
    }

    /** @test */
    public function current_password_does_not_match()
    {
        $this->loginAsAdmin();

        $user = $this->admin();

        $response = $this->from($this->getRoute($user->name))
                         ->post($this->postRoute($user->id), [
                            'current-password'          => 'laravel',
                            'new-password'              => 'new-awesome-password',
                            'new-password_confirmation' => 'new-awesome-password',
                         ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('current-password');

        $this->assertFalse(
            Hash::check(
                'new-awesome-password',
                $user->fresh()->password
            )
        );
    }

    /**
     * @test
     * @dataProvider newPasswordFail
     */
    public function new_password_validate_fail($data1, $data2)
    {
        $this->loginAsAdmin();

        $user = $this->user();

        $response = $this->from($this->getRoute($user->name))
                         ->post($this->postRoute($user->id), [
                            'current-password'          => $this->adminPassword(),
                            'new-password'              => $data1,
                            'new-password_confirmation' => $data2,
                         ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('new-password');

        $this->assertFalse(
            Hash::check(
                $data1,
                $user->fresh()->password
            )
        );
    }

    public function newPasswordFail()
    {
        return [
            ['', ''], // required
            [$this->adminPassword(), $this->adminPassword()], // different
            [null, null], // string
            [str_repeat('a', 5), str_repeat('a', 5)], // min:6
            ['new-password', 'new-pass-word'], // confirmed
        ];
    }
}
