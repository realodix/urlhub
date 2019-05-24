<?php

namespace Tests\Support;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

trait Authentication
{
    /** @var User $user * */
    protected $user;

    public function setupUser()
    {
        $this->user = factory(User::class)->create();
    }

    public function authenticated(Authenticatable $user = null)
    {
        return $this->actingAs($user ?? $this->user);
    }
}
