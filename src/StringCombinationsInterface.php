<?php

namespace BenTools\StringCombinations;

interface StringCombinationsInterface extends \Traversable, \Countable
{

    /**
     * Creates a random string from current charset
     * @return string
     */
    public function getRandomString();

    /**
     * @return array
     */
    public function asArray();
}
