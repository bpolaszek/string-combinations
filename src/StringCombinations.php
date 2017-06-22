<?php

namespace BenTools\StringCombinations;

use function BenTools\CartesianProduct\cartesian_product;

class StringCombinations implements \IteratorAggregate, \Countable
{
    /**
     * @var string[]
     */
    private $charset;

    /**
     * @var int
     */
    private $min;

    /**
     * @var int
     */
    private $max;

    /**
     * @var int
     */
    private $count;

    /**
     * @var string
     */
    private $glue;

    /**
     * StringCombination constructor.
     * @param mixed  $charset
     * @param int    $min
     * @param int    $max
     * @param string $glue
     * @throws \InvalidArgumentException
     */
    public function __construct($charset, $min = 1, $max = null, $glue = '')
    {
        if (is_string($charset) || is_integer($charset)) {
            $this->charset = preg_split('/(?<!^)(?!$)/u', $charset);
            $this->validateCharset($this->charset);
        } elseif (is_array($charset)) {
            $this->charset = $charset;
            $this->validateCharset($this->charset);
        } else {
            $this->denyCharset();
        }
        $this->min = (int) $min;
        $this->max = is_null($max) ? count($this->charset) : (int) $max;
        $this->glue = $glue;
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        if (null === $this->count) {
            $this->count = array_sum(array_map(function ($set) {
                return count(cartesian_product($set));
            }, iterator_to_array($this->generateSets())));
        }
        return $this->count;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        foreach ($this->generateSets() as $set) {
            foreach (cartesian_product($set) as $combination) {
                yield implode($this->glue, $combination);
            }
        }
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return iterator_to_array($this);
    }

    /**
     * @return \Generator
     */
    private function generateSets()
    {
        for ($i = $this->min; $i <= $this->max; $i++) {
            $set = array_fill(0, $i, $this->charset);
            yield $set;
        }
    }

    private function validateCharset($charset)
    {
        if (is_null($charset)) {
            $this->denyCharset();
        }
        foreach ($charset as $value) {
            if (!is_string($value) && !is_integer($value)) {
                $this->denyCharset();
            }
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function denyCharset()
    {
        throw new \InvalidArgumentException('Charset should be a string or an array of strings.');
    }
}
