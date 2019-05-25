<?php

namespace Tests\Feature\User;

use Tests\TestCase;

class ProfileTest extends TestCase
{
    protected function profileGetRoute($value)
    {
        return route('user.edit', $value);
    }

    protected function profilePostRoute($value)
    {
        return route('user.update', \Hashids::connection(\App\User::class)->encode($value));
    }

    /** @test */
    public function admin_can_access_a_user_profile_page()
    {
        $this->loginAsAdmin();

        $response = $this->get($this->profileGetRoute($this->user()->name));
        $response->assertStatus(200);
    }

    /** @test */
    public function user_cant_access_a_admin_profile_page()
    {
        $this->loginAsUser();

        $response = $this->get($this->profileGetRoute($this->admin()->name));
        $response->assertStatus(403);
    }

    /** @test */
    public function validation_email_required()
    {
        $this->loginAsAdmin();

        $response = $this->from($this->profileGetRoute($this->admin()->name))
                         ->post($this->profilePostRoute($this->admin()->id), [
                            'email' => '',
                         ]);

        $response
            ->assertRedirect($this->profileGetRoute($this->admin()->name))
            ->assertStatus(302)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function validation_email_invalid_format()
    {
        $this->loginAsAdmin();

        $response = $this->from($this->profileGetRoute($this->admin()->name))
                         ->post($this->profilePostRoute($this->admin()->id), [
                            'email' => 'invalid_format',
                         ]);

        $response
            ->assertRedirect($this->profileGetRoute($this->admin()->name))
            ->assertStatus(302)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function validation_email_unique()
    {
        $this->loginAsAdmin();

        $response = $this->from($this->profileGetRoute($this->admin()->name))
                         ->post($this->profilePostRoute($this->admin()->id), [
                            'email' => $this->user()->email,
                         ]);

        $response
            ->assertRedirect($this->profileGetRoute($this->admin()->name))
            ->assertStatus(302)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function admin_can_change_user_email()
    {
        $this->loginAsAdmin();

        $response = $this->from($this->profileGetRoute($this->user()->name))
                         ->post($this->profilePostRoute($this->user()->id), [
                            'email' => 'new_email_user@urlhub.test',
                         ]);

        $response
            ->assertRedirect($this->profileGetRoute($this->user()->name))
            ->assertSessionHas(['flash_success']);

        $this->assertSame('new_email_user@urlhub.test', $this->user()->email);
    }

    /** @test */
    public function user_cant_change_admin_email()
    {
        $this->loginAsUser();

        $response = $this->from($this->profileGetRoute($this->admin()->name))
                         ->post($this->profilePostRoute($this->admin()->id), [
                            'email' => 'new_email_admin@urlhub.test',
                         ]);

        $response->assertStatus(403);
        $this->assertSame('admin@urlhub.test', $this->admin()->email);
    }
}
