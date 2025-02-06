<?php

namespace App\Rules;

use Illuminate\Validation\Rules\Password;

class PasswordRules
{
    public static function rule(): array
    {
        return [Password::min(5), 'confirmed'];
    }
}
