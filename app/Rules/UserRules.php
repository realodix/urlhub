<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRules
{
    public static function name(): array
    {
        return [
            'string', 'alpha_num:ascii', 'max:20',
            Rule::unique(User::class, 'name'),
            Rule::notIn(['guest', 'guests']),
        ];
    }

    public static function email(): array
    {
        return ['string', 'email', 'max:255', Rule::unique(User::class)];
    }

    public static function password(): array
    {
        return ['string', Password::min(5)];
    }

    public static function passwordWithConfirm(): array
    {
        return [...self::password(), 'confirmed'];
    }
}
