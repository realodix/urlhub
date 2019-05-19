<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Blacklist implements Rule
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
        $black_list = remove_schemes(config('urlhub.blacklist'));
        $long_url = rtrim($value, '/');

        foreach ($black_list as $blacklist) {
            $url_segment = ('://'.$blacklist.'/');
            $url_segment2 = ('://www.'.$blacklist.'/');
        }

        return ! (strstr($long_url, $url_segment) || strstr($long_url, $url_segment2));
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
