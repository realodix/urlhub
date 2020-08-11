<?php

use App\Helpers\General\NumHelper;

if (! function_exists('numberFormatShort')) {
    function numberFormatShort($value)
    {
        return resolve(NumHelper::class)->numberFormatShort($value);
    }
}
