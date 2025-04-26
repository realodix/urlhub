<?php

namespace App\Rules;

use Composer\Pcre\Preg;

class LinkRules
{
    /**
     * Maximum length of link.
     *
     * @var int
     */
    const LENGTH = 7000;

    public static function link(): array
    {
        return [
            'max:'.self::LENGTH, new NotBlacklistedDomain,
            function ($attribute, $value, $fail) {
                if (!Preg::isMatch('/^[a-zA-Z][a-zA-Z0-9+.-]*:\/\/[^\s]+$/', $value)) {
                    $fail('The :attribute field must be a valid link.');
                }
            },
        ];
    }

    public static function customKeyword(): array
    {
        $settings = app(\App\Settings\GeneralSettings::class);
        $minLen = $settings->cst_key_min_len;
        $maxLen = $settings->cst_key_max_len;

        return [
            new AlphaNumUnderscore,
            "min:{$minLen}", "max:{$maxLen}", 'lowercase:field',
            'unique:App\Models\Url,keyword',
            new NotBlacklistedKeyword,
        ];
    }
}
