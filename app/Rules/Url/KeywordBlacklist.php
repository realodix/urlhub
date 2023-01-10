<?php

namespace App\Rules\Url;

use App\Services\KeyGeneratorService;
use Illuminate\Contracts\Validation\InvokableRule;

/**
 * Check if keyword id is free (ie not already taken, not a URL path, and not
 * reserved).
 */
class KeywordBlacklist implements InvokableRule
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
        $stringCanBeUsedAsKey = app(KeyGeneratorService::class)->assertStringCanBeUsedAsKey($value);

        if ($stringCanBeUsedAsKey === false) {
            $fail('Not available.');
        }
    }
}
