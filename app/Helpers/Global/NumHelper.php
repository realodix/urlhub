<?php

use App\Helpers\General\NumHelper;

if (! function_exists('numberFormatShort')) {
    function numberFormatShort($value)
    {
        return resolve(NumHelper::class)->numberFormatShort($value);
    }
}

if (! function_exists('remainingPercentage')) {
    function remainingPercentage($remaining, $capacity)
    {
        return resolve(NumHelper::class)->remainingPercentage($remaining, $capacity);
    }
}
