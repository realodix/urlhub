<?php

namespace App\Actions\Fortify;

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
            'name' => ['required', ...UserRules::name()],
            'email' => ['required', ...UserRules::email()],
            'password' => ['required', ...UserRules::passwordWithConfirm()],
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => strtolower($input['email']),
            'password' => $input['password'],
        ]);
    }
}
