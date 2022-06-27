<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * The field under validation may have alpha-numeric characters, as well as
 * underscores.
 */
class StrAlphaUnderscore implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  mixed  $value
     */
    public function passes(string $attribute, $value): bool
    {
        return preg_match('/^[\pL\pM\pN_]+$/u', $value) > 0;
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
        return 'The :attribute may only contain letters, numbers and underscores.';
    }
}
