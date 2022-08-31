<?php

use App\Helpers\General\NumHelper;

if (! function_exists('numberToAmountShort')) {
    /**
     * \App\Helpers\General\NumHelper::numberToAmountShort()
     *
     * @return int|string
     */
    function numberToAmountShort($value)
    {
        return resolve(NumHelper::class)->numberToAmountShort($value);
    }
}
