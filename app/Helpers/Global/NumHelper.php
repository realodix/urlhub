<?php

use App\Helpers\NumHelper;

if (! function_exists('numberToAmountShort')) {
    /**
     * \App\Helpers\NumHelper::numberToAmountShort()
     *
     * @param int $value
     * @return int|string
     */
    function numberToAmountShort($value)
    {
        return NumHelper::numberToAmountShort($value);
    }
}
