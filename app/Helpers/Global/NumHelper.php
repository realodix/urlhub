<?php

use App\Helpers\NumHelper;

if (! function_exists('numberShorten')) {
    /**
     * \App\Helpers\NumHelper::numberShorten()
     *
     * @param int $value
     * @return int|string
     */
    function numberShorten($value)
    {
        return NumHelper::number_shorten($value);
    }
}
