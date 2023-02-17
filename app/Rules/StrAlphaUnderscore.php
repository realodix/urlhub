<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

/**
 * The field under validation may have alpha-numeric characters, as well as
 * underscores.
 */
class StrAlphaUnderscore implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $rule = preg_match('/^[\pL\pM\pN_]+$/u', $value);

        if ($rule === false || $rule === 0) {
            $fail('The :attribute may only contain letters, numbers and underscores.');
        }
    }
}
