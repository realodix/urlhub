<?php

namespace App\Helpers;

class NumHlp
{
    /**
     * Convert large positive numbers in to short form like 1K+, 100K+, 199K+,
     * 1M+, 10M+, 1B+ etc.
     * Based on: ({@link https://gist.github.com/RadGH/84edff0cc81e6326029c}).
     *
     * @param int $n
     * @return string
     */
    public function number_format_short(int $n)
    {
        if ($n >= 0 && $n < pow(10, 3)) {
            // 1 - 999
            $n_format = floor($n);
            $suffix = '';
        } elseif ($n >= pow(10, 3) && $n < pow(10, 6)) {
            // 1k-999k
            $n_format = round($n / pow(10, 3));
            $suffix = 'K+';

            if (($n / pow(10, 3) == 1) || ($n / pow(10, 4) == 1) || ($n / pow(10, 5) == 1)) {
                $suffix = 'K';
            }
        } elseif ($n >= pow(10, 6) && $n < pow(10, 9)) {
            // 1m-999m
            $n_format = round($n / pow(10, 6));
            $suffix = 'M+';

            if (($n / pow(10, 6) == 1) || ($n / pow(10, 7) == 1) || ($n / pow(10, 8) == 1)) {
                $suffix = 'M';
            }
        } elseif ($n >= pow(10, 9) && $n < pow(10, 12)) {
            // 1b-999b
            $n_format = round($n / pow(10, 9));
            $suffix = 'B+';

            if (($n / pow(10, 9) == 1) || ($n / pow(10, 10) == 1) || ($n / pow(10, 11) == 1)) {
                $suffix = 'B';
            }
        } elseif ($n >= pow(10, 12)) {
            // 1t+
            $n_format = round($n / pow(10, 12));
            $suffix = 'T+';

            if (($n / pow(10, 12) == 1) || ($n / pow(10, 13) == 1) || ($n / pow(10, 14) == 1)) {
                $suffix = 'T';
            }
        }

        return ! empty($n_format.$suffix) ? $n_format.$suffix : 0;
    }

    /**
     * Alternative to make number_format() not to round numbers up.
     *
     * @param number $number
     * @param int    $precision
     * @param string $separator
     * @return number
     */
    public function number_format_precision($number, $precision = 2, $separator = '.')
    {
        // https://stackoverflow.com/a/40125597
        $numberParts = explode($separator, $number);
        $response = $numberParts[0];

        if (count($numberParts) > 1) {
            $response .= $separator;
            $response .= substr($numberParts[1], 0, $precision);
        }

        // Remove useless zero digits from decimals
        // https://stackoverflow.com/a/14531760
        return $response + 0;
    }
}
