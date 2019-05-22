<?php

namespace Tests\Feature\User;

use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class Profile extends TestCase
{
    use MigrateFreshSeedOnce;

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
}
