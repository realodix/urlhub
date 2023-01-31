<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

/**
 * The field under validation may have alpha-numeric characters, as well as
 * underscores.
 */
class StrAlphaUnderscore implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param string   $attribute
     * @param mixed    $value
     * @param \Closure $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $rule = preg_match('/^[\pL\pM\pN_]+$/u', $value);

        if ($rule === false || $rule === 0) {
            $fail('The :attribute may only contain letters, numbers and underscores.');
        }
    }
}
