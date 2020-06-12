<?php

use App\Helpers\General\NumHelper;

if (! function_exists('number_format_short')) {
    function number_format_short($value)
    {
        return resolve(NumHelper::class)->number_format_short($value);
    }
}
