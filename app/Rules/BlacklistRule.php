<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BlacklistRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $blacklist = remove_schemes(config('urlhub.blacklist'));
        $long_url = rtrim($value, '/');
        $a = true;

        foreach ($blacklist as $black_list) {
            $url_segment = ('://'.$black_list.'/');
            $url_segment2 = ('://www.'.$black_list.'/');

            if ((strstr($long_url, $url_segment) || strstr($long_url, $url_segment2))) {
                $a = false;
            }
        }

        return $a;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sorry, the URL you entered is on our internal blacklist. It may have been used abusively in the past, or it may link to another URL redirection service.';
    }
}
