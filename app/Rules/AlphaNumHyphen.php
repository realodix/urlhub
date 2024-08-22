<?php

namespace App\Rules;

use Composer\Pcre\Preg;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * The field under validation may have alpha-numeric characters, as well as
 * hyphen.
 */
class AlphaNumHyphen implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (! Preg::isMatch('/^[\pL\pM\pN-]+$/u', $value)) {
            $fail('The :attribute may only contain letters, numbers and hyphens.');
        }
    }
}
