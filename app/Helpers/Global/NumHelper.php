<?php

use App\Helpers\General\NumHelper;

if (!function_exists('numberToAmountShort')) {
    function numberToAmountShort($value)
    {
        return resolve(NumHelper::class)->numberToAmountShort($value);
    }
}
