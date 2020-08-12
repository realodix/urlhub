<?php

use App\Helpers\General\HtmlHelper;

if (! function_exists('style')) {
    /**
     * @param string $url
     * @param array  $attributes
     * @param null   $secure
     *
     * @return mixed
     */
    function style($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->style($url, $attributes, $secure);
    }
}

if (! function_exists('script')) {
    /**
     * @param string $url
     * @param array  $attributes
     * @param null   $secure
     *
     * @return mixed
     */
    function script($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->script($url, $attributes, $secure);
    }
}
