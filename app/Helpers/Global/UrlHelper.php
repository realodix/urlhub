<?php

use App\Helpers\General\UrlHelper;

if (! function_exists('urlLimit')) {
    function urlLimit($url, $maxlength = 50)
    {
        return resolve(UrlHelper::class)->urlLimit($url, $maxlength);
    }
}

if (! function_exists('urlRemoveScheme')) {
    function urlRemoveScheme($value)
    {
        return resolve(UrlHelper::class)->urlRemoveScheme($value);
    }
}
