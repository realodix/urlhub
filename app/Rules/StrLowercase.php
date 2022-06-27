<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrLowercase implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
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
