<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    protected function getRoute($value)
    {
        return route('user.edit', $value);
    }

    protected function postRoute($value)
    {
        return route('user.update', \Hashids::connection(\App\Models\User::class)->encode($value));
    }

    /**
     * @test
     * @group f-user
     */
    public function users_can_access_their_own_profile_page()
    {
        $this->loginAsAdmin();

        $response = $this->get($this->getRoute($this->admin()->name));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-user
     */
    public function admin_can_access_other_users_profile_pages()
    {
        $this->loginAsAdmin();

        $response = $this->get($this->getRoute($this->user()->name));
        $response->assertOk();
    }

    /**
     * @test
     * @group f-user
     */
    public function non_admin_cant_access_other_users_profile_pages()
    {
        $this->loginAsUser();

        $response = $this->get($this->getRoute($this->admin()->name));
        $response->assertForbidden();
    }

    /**
     * @test
     * @group f-user
     */
    public function admin_can_change_other_users_email()
    {
        $this->loginAsAdmin();

        $user = User::factory()->create(['email' => 'user_email@urlhub.test']);

        $response =
            $this
                ->from($this->getRoute($user->name))
                ->post($this->postRoute($user->id), [
                    'email' => 'new_user_email@urlhub.test',
                ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHas('flash_success');

        $this->assertSame('new_user_email@urlhub.test', $user->fresh()->email);
    }

    /**
     * @test
     * @group f-user
     */
    public function non_admin_cant_change_other_users_email()
    {
        $this->loginAsUser();

        $user = User::factory()->create(['email' => 'user2@urlhub.test']);

        $response =
            $this
                ->from($this->getRoute($user->name))
                ->post($this->postRoute($user->id), [
                    'email' => 'new_email_user2@urlhub.test',
                ]);

        $response->assertForbidden();
        $this->assertSame('user2@urlhub.test', $user->email);
    }

    /**
     * @test
     * @group f-user
     */
    public function validation_email_required()
    {
        $this->loginAsAdmin();

        $user = $this->admin();

        $response =
            $this
                ->from($this->getRoute($user->name))
                ->post($this->postRoute($user->id), [
                    'email' => '',
                ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }

    /**
     * @test
     * @group f-user
     */
    public function validation_email_invalid_format()
    {
        $this->loginAsAdmin();

        $user = $this->admin();

        $response =
            $this
                ->from($this->getRoute($user->name))
                ->post($this->postRoute($user->id), [
                    'email' => 'invalid_format',
                ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }

    /**
     * @test
     * @group f-user
     */
    public function validation_email_max_length()
    {
        $this->loginAsAdmin();

        $user = $this->admin();

        $response =
            $this
                ->from($this->getRoute($user->name))
                ->post($this->postRoute($user->id), [
                    // 255 + 9
                    'email' => str_repeat('a', 255).'@mail.com',
                ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }

    /**
     * @test
     * @group f-user
     */
    public function validation_email_unique()
    {
        $this->loginAsAdmin();

        $user = $this->admin();

        $response =
            $this
                ->from($this->getRoute($user->name))
                ->post($this->postRoute($user->id), [
                    'email' => $this->user()->email,
                ]);

        $response
            ->assertRedirect($this->getRoute($user->name))
            ->assertSessionHasErrors('email');
    }
}
