<?php

namespace App\Rules;

use App\Services\KeyGeneratorService;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Check if keyword id is free (ie not already taken, not a URL path, and not
 * reserved).
 */
class NotBlacklistedKeyword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $stringCanBeUsedAsKey = app(KeyGeneratorService::class)->verify($value);

        if ($stringCanBeUsedAsKey === false) {
            $fail('Not available.');
        }
    }
}
