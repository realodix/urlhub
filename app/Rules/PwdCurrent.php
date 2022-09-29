<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\{Auth, Hash};

class PwdCurrent implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Hash::check($value, Auth::user()->password);
    }

    /**
     * @codeCoverageIgnore
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The password you entered does not match your password. Please try again.';
    }
}
