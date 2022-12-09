<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PwdCurrent implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (! Hash::check($value, Auth::user()->password)) {
            $fail('The password you entered does not match your password. Please try again.');
        }
    }
}
