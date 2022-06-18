<?php

if (!function_exists('roundDown')) {
    function roundDown($number, $precision)
    {
        if ($number == 0) {
            return 0;
        }
        $half = 0.5 / 10 ** $precision;
        return round($number - $half, $precision);
    }
}