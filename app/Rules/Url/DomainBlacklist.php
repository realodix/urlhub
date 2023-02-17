<?php

namespace App\Rules\Url;

use App\Helpers\Helper;
use Illuminate\Contracts\Validation\ValidationRule;

class DomainBlacklist implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $blackLists = config('urlhub.domain_blacklist');
        $longUrl = rtrim($value, '/');
        $bool = true;

        foreach ($blackLists as $blackList) {
            $blackList = Helper::urlDisplay($blackList, scheme: false);
            $segment1 = '://'.$blackList.'/';
            $segment2 = '://www.'.$blackList.'/';

            if (strstr($longUrl, $segment1) || strstr($longUrl, $segment2)) {
                $bool = false;
            }
        }

        if ($bool === false) {
            $fail('Sorry, the URL you entered is on our internal blacklist. '.
            'It may have been used abusively in the past, or it may link to another URL redirection service.');
        }
    }
}
