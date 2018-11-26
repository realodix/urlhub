<?php

namespace App\Helpers;

class NumHlp
{
    /**
     * Convert large positive numbers in to short form like 1K+, 100K+, 199K+,
     * 1M+, 10M+, 1B+ etc.
     * Based on: {@link https://gist.github.com/RadGH/84edff0cc81e6326029c}
     *
     * @param int $n
     * @return string
     */
    public function readable_int($n)
    {
        if ($n >= 0 && $n < 1000) {
            // 1 - 999
            $n_format = floor($n);
            $suffix = '';
        } elseif ($n >= 1000 && $n < 1000000) {
            // 1k-999k
            $n_format = $this->number_format_precision($n / 1000, 1);
            $suffix = 'K+';
        } elseif ($n >= 1000000 && $n < 1000000000) {
            // 1m-999m
            $n_format = $this->number_format_precision($n / 1000000);
            $suffix = 'M+';
        } elseif ($n >= 1000000000 && $n < 1000000000000) {
            // 1b-999b
            $n_format = $this->number_format_precision($n / 1000000000);
            $suffix = 'B+';
        } elseif ($n >= 1000000000000) {
            // 1t+
            $n_format = $this->number_format_precision($n / 1000000000000);
            $suffix = 'T+';
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
