<?php

namespace BenTools\StringCombinations;

require_once __DIR__ . '/StringCombinations.php';

/**
 * @param $charset
 * @param int      $min
 * @param int|null $max
 * @return StringCombinations
 */
function string_combinations($charset, $min = 1, $max = null, $glue = '')
{
    return new StringCombinations($charset, $min, $max, $glue);
}
