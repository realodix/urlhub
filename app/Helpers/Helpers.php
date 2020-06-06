<?php

use App\Helpers\HtmlHlp;
use App\Helpers\NumHlp;
use App\Helpers\UrlHlp;

/*
|-----------------------------------------------------------------------------
| Global Helpers
|-----------------------------------------------------------------------------
|
| Here is where you can register helper functions for your application. These
| helper functions can be uesed anywhere in you application. Now create
| something great!
|
*/

/*
|--------------------------------------------------------------------------
| Num Helpers
|--------------------------------------------------------------------------
*/
if (! function_exists('number_format_short')) {
    function number_format_short($value)
    {
        return resolve(NumHlp::class)->number_format_short($value);
    }
}

/*
|--------------------------------------------------------------------------
| URL Helpers
|--------------------------------------------------------------------------
*/
if (! function_exists('url_limit')) {
    function url_limit($url, $maxlength = 50)
    {
        return resolve(UrlHlp::class)->url_limit($url, $maxlength);
    }
}

if (! function_exists('remove_schemes')) {
    function remove_schemes($value)
    {
        return resolve(UrlHlp::class)->remove_schemes($value);
    }
}

/*
|--------------------------------------------------------------------------
| HTML Helpers
|--------------------------------------------------------------------------
*/
if (! function_exists('style')) {
    /**
     * @param       $url
     * @param array $attributes
     * @param null  $secure
     *
     * @return mixed
     */
    function style($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHlp::class)->style($url, $attributes, $secure);
    }
}

if (! function_exists('script')) {
    /**
     * @param       $url
     * @param array $attributes
     * @param null  $secure
     *
     * @return mixed
     */
    function script($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHlp::class)->script($url, $attributes, $secure);
    }
}
