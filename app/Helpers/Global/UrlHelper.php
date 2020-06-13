<?php

use App\Helpers\General\UrlHelper;

if (! function_exists('urlLimit')) {
    function urlLimit($url, $maxlength = 50)
    {
        return resolve(UrlHelper::class)->urlLimit($url, $maxlength);
    }
}

if (! function_exists('remove_schemes')) {
    function remove_schemes($value)
    {
        return resolve(UrlHelper::class)->remove_schemes($value);
    }
}
