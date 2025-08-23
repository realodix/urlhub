<?php

namespace App\Rules;

use App\Helpers\Helper;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedDomain implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (Helper::isDomainBlacklisted($value)) {
            $fail(
                'Sorry, the URL you entered is on our internal blacklist. '
                .'It may have been used abusively in the past, or it may link to another URL redirection service.',
            );
        }
    }
}
