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

    public static function rules(): array
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
}
