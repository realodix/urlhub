<?php

namespace App\Rules;

use Composer\Pcre\Preg;

class LinkRules
{
    /**
     * Maximum length of link
     *
     * @var int
     */
    const MAX_LENGTH = 7000;

    /**
     * Maximum length of title
     *
     * @var int
     */
    const TITLE_MAX_LENGTH = 255;

    /**
     * The minimum length of the password
     *
     * @var int
     */
    const PWD_MIN_LENGTH = 3;

    public static function link(): array
    {
        return [
            'max:'.self::MAX_LENGTH, new AllowedDomain,
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
            new AlphaNumDash,
            "min:{$minLen}", "max:{$maxLen}",
            'unique:App\Models\Url,keyword',
            new AllowedKeyword,
        ];
    }
}
