<?php

namespace Tests\Feature\User;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class ChangePasswordTest extends TestCase
{
    protected function getRoute($value)
    {
        return route('user.change-password', $value);
    }

    protected function postRoute($value)
    {
        $hashids = Hashids::connection(\App\Models\User::class);

        return route('user.change-password.post', $hashids->encode($value));
    }

    /**
     * @test
     * @group f-user
     */
    public function changePasswordWithCorrectCredentials()
    {
        $this->actingAs($this->admin());

        $user = $this->admin();

        $response = $this->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'current-password'          => $this->adminPass(),
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

    /**
     * @test
     * @group f-user
     */
    public function adminCanChangeThePasswordOfAllUsers()
    {
        $this->actingAs($this->admin());

        $user = $this->nonAdmin();

        $response = $this->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'current-password'          => $this->adminPass(),
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

    /**
     * @test
     * @group f-user
     */
    public function currentPasswordDoesNotMatch()
    {
        $this->actingAs($this->admin());

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
     * @group f-user
     * @dataProvider newPasswordFailProvider
     *
     * @param mixed $data1
     * @param mixed $data2
     */
    public function newPasswordValidateFail($data1, $data2)
    {
        $this->actingAs($this->admin());

        $user = $this->nonAdmin();

        $response = $this->from($this->getRoute($user->name))
            ->post($this->postRoute($user->id), [
                'current-password'          => $this->adminPass(),
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

    public function newPasswordFailProvider()
    {
        return [
            ['', ''], // required
            [$this->adminPass(), $this->adminPass()], // different
            [null, null], // string
            ['new-password', 'new-pass-word'], // confirmed

            // Laravel NIST Password Rules
            // ['new-awe', 'new-awe'], // min:8
            // [str_repeat('a', 9), str_repeat('a', 9)], // repetitive
            // ['12345678', '12345678'], // sequential
        ];
    }
}
