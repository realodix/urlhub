<?php

namespace App\Helpers\General;

class NumHelper
{
    /**
     * Convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+,
     * 1B+ etc.
     * Based on: ({@link https://gist.github.com/RadGH/84edff0cc81e6326029c}).
     */
    public function numberToAmountShort(int $number): int|string
    {
        $nFormat = floor($number);
        $suffix = '';

        if ($number >= pow(10, 3) && $number < pow(10, 6)) {
            // 1k-999k
            $nFormat = $this->numbPrec($number / pow(10, 3));
            $suffix = 'K+';

            if (($number / pow(10, 3) === 1) || ($number / pow(10, 4) === 1) || ($number / pow(10, 5) === 1)) {
                $suffix = 'K';
            }
        } elseif ($number >= pow(10, 6) && $number < pow(10, 9)) {
            // 1m-999m
            $nFormat = $this->numbPrec($number / pow(10, 6));
            $suffix = 'M+';

            if (($number / pow(10, 6) === 1) || ($number / pow(10, 7) === 1) || ($number / pow(10, 8) === 1)) {
                $suffix = 'M';
            }
        } elseif ($number >= pow(10, 9) && $number < pow(10, 12)) {
            // 1b-999b
            $nFormat = $this->numbPrec($number / pow(10, 9));
            $suffix = 'B+';

            if (($number / pow(10, 9) === 1) || ($number / pow(10, 10) === 1) || ($number / pow(10, 11) === 1)) {
                $suffix = 'B';
            }
        } elseif ($number >= pow(10, 12)) {
            // 1t+
            $nFormat = $this->numbPrec($number / pow(10, 12));
            $suffix = 'T+';

            if (($number / pow(10, 12) === 1) || ($number / pow(10, 13) === 1) || ($number / pow(10, 14) === 1)) {
                $suffix = 'T';
            }
        }

        return ! empty($nFormat.$suffix) ? $nFormat.$suffix : 0;
    }

    /**
     * Alternative to make number_format() not to round numbers up.
     *
     * Based on: (@see https://stackoverflow.com/q/3833137).
     */
    public function numbPrec(float $number, int $precision = 2): float
    {
        return floor($number * pow(10, $precision)) / pow(10, $precision);
    }
}
