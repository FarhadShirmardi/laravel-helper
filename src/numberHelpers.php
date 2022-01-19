<?php

if (!function_exists('roundDown')) {
    function roundDown($number, $precision)
    {
        $half = 0.5 / 10 ** $precision;
        return round($number - $half, $precision);
    }
}