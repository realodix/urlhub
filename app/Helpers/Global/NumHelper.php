<?php

use App\Helpers\General\NumHelper;

if (! function_exists('numberToAmountShort')) {
    /**
     * \App\Helpers\General\NumHelper::numberToAmountShort()
     *
     * @param int $value
     * @return int|string
     */
    function numberToAmountShort($value)
    {
        return NumHelper::numberToAmountShort($value);
    }
}
