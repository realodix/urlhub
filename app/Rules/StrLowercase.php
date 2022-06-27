<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrLowercase implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  mixed  $value
     */
    public function passes(string $attribute, $value): bool
    {
        return strtolower($value) === $value;
    }

    /**
     * Get the validation error message.
     *
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be Lowercase.';
    }
}
