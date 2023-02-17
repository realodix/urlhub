<?php

namespace App\Rules\Url;

use App\Services\KeyGeneratorService;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Check if keyword id is free (ie not already taken, not a URL path, and not
 * reserved).
 */
class KeywordBlacklist implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $stringCanBeUsedAsKey = app(KeyGeneratorService::class)->assertStringCanBeUsedAsKey($value);

        if ($stringCanBeUsedAsKey === false) {
            $fail('Not available.');
        }
    }
}
