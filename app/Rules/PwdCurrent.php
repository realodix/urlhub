<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class PwdCurrent implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        /** @var \App\Models\User */
        $user = auth()->user();

        if (! Hash::check($value, $user->password)) {
            $fail('The password you entered does not match your password. Please try again.');
        }
    }
}
