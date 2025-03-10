<?php

namespace App\Services\Fortify;

use App\Models\User;
use App\Rules\UserRules;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Validate and create a newly registered user.
     *
     * @return User \App\Models\User
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name'     => UserRules::name(),
            'email'    => UserRules::email(),
            'password' => UserRules::passwordWithConfirm(),
        ])->validate();

        return User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => $input['password'],
        ]);
    }
}
