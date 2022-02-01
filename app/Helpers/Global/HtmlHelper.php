<?php

use App\Helpers\General\HtmlHelper;

if (! function_exists('style')) {
    /**
     * @param  string  $url
     * @param  array  $attributes
     * @param  null  $secure
     * @return mixed
     */
    function style(string $url, array $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->style($url, $attributes, $secure);
    }
}

if (! function_exists('script')) {
    /**
     * @param  string  $url
     * @param  array  $attributes
     * @param  null  $secure
     * @return mixed
     */
    function script(string $url, array $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->script($url, $attributes, $secure);
    }
}
