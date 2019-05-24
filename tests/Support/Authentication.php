<?php

namespace Tests\Support;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

trait Authentication
{
    protected $user;

    public function setupUser()
    {
        $attributes = [];

        if (method_exists($this, 'getUserFactoryAttributes')) {
            $attributes = $this->getUserFactoryAttributes();
        }

        $this->user = factory(User::class)->create($attributes);
    }

    public function authenticated(Authenticatable $user = null)
    {
        return $this->actingAs($user ?? $this->user);
    }
}
