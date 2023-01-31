<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Hash;

class PwdCurrent implements InvokableRule
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
        /** @var \App\Models\User */
        $user = auth()->user();

        if (! Hash::check($value, $user->password)) {
            $fail('The password you entered does not match your password. Please try again.');
        }
    }
}
