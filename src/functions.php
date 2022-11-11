<?php

namespace BenTools\StringCombinations;

require_once __DIR__ . '/StringCombinations.php';

/**
 * @param string|array $charset
 * @return StringCombinations
 */
function string_combinations($charset, int $min = 1, ?int $max = null, string $glue = ''): StringCombinations
{
    return new StringCombinations($charset, $min, $max, $glue);
}
