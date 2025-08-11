<?php

namespace App\Rules;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRules
{
    public static function name(): array
    {
        return [
            'string', 'alpha_num:ascii', 'lowercase', 'min:4', 'max:20',
            'unique:App\Models\User,name',
            Rule::notIn(['guest', 'guests']),
            Rule::notIn(config('urlhub.blacklist_username')),
        ];
    }

    public static function email(): array
    {
        return ['string', 'email', 'max:255', 'unique:App\Models\User,email'];
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
