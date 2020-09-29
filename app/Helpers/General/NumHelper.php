<?php

namespace App\Helpers\General;

use Realodix\Utils\Number\Number;

class NumHelper
{
    public function numberToAmountShort(int $number)
    {
        return Number::toAmountShort($number);
    }
}
