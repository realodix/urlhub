<?php

namespace App\Rules;

use Illuminate\Validation\Rules\Password;

class PasswordRules
{
    public static function rule(): array
    {
        return ['required', 'string', Password::min(5)];
    }

    public static function ruleWithConfirm(): array
    {
        return [...self::rule(), 'confirmed'];
    }
}
