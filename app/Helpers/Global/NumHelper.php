<?php

use App\Helpers\NumHelper;

if (! function_exists('compactNumber')) {
    /**
     * \App\Helpers\NumHelper::compactNumber()
     *
     * @param int $value
     * @return int|string
     */
    function compactNumber($value)
    {
        return NumHelper::number_shorten($value);
    }
}
