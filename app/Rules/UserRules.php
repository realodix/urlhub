<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRules
{
    public static function name(): array
    {
        return ['required', 'string', 'max:20'];
    }

    public static function email(): array
    {
        return ['required', 'string', 'email', 'max:255', Rule::unique(User::class)];
    }

    public static function password(): array
    {
        return ['required', 'string', Password::min(5)];
    }

    public static function passwordWithConfirm(): array
    {
        return [...self::password(), 'confirmed'];
    }
}
