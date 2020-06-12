<?php

use App\Helpers\General\UrlHelper;

if (! function_exists('url_limit')) {
    function url_limit($url, $maxlength = 50)
    {
        return resolve(UrlHelper::class)->url_limit($url, $maxlength);
    }
}

if (! function_exists('remove_schemes')) {
    function remove_schemes($value)
    {
        return resolve(UrlHelper::class)->remove_schemes($value);
    }
}
