<?php

namespace App\Services\Fortify;

use App\Models\User;
use App\Rules\UserRules;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    /**
     * Validate and reset the user's forgotten password.
     *
     * @param array<string, string> $input
     */
    public function reset(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => UserRules::passwordWithConfirm(),
        ])->validate();

        $user->forceFill([
            'password' => $input['password'],
        ])->save();
    }
}
