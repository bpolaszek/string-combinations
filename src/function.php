<?php

namespace BenTools\StringCombinations;

require_once __DIR__ . '/StringCombinations.php';

/**
 * @param $charset
 * @param int      $min
 * @param int|null $max
 * @param string   $glue
 * @return StringCombinations
 */
function string_combinations($charset, $min = 1, $max = null, $glue = '')
{
    return new StringCombinations($charset, $min, $max, $glue);
}

/**
 * @param $number
 * @return float|int
 */
function fact($number)
{
    if (extension_loaded('gmp')) {
        return gmp_intval(gmp_fact($number));
    }
    for ($x = $number, $factorial = 1; $x >= 1; $x--) {
        $factorial = $factorial * $x;
    }
    return $factorial;
}