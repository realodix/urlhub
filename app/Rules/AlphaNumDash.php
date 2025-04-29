<?php

namespace App\Rules;

use Composer\Pcre\Preg;
use Illuminate\Contracts\Validation\ValidationRule;

class AlphaNumDash implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (!is_string($value) || !Preg::isMatch('/\A[a-zA-Z0-9-]+\z/u', $value)) {
            $fail('The :attribute may only contain letters, numbers and dashes.');
        }
    }
}
