<?php

if (!function_exists('roundDown')) {
    function roundDown($number, $precision)
    {
        $multiplier = 10 ** $precision;
        return floor($number * $precision) / $precision;
    }
}