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
        $minLen = $settings->custom_keyword_min_length;
        $maxLen = $settings->custom_keyword_max_length;

        return [
            "min:{$minLen}", "max:{$maxLen}", 'lowercase:field',
            'unique:App\Models\Url,keyword',
            new AlphaNumUnderscore,
            new NotBlacklistedKeyword,
        ];
    }
}
