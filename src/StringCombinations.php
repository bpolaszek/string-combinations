<?php

namespace BenTools\StringCombinations;

use function BenTools\CartesianProduct\cartesian_product;
use Countable;
use IteratorAggregate;

/**
 * @property $min
 * @property $max
 * @property $charset
 * @property $glue
 */
final class StringCombinations implements IteratorAggregate, Countable
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
        if (is_string($charset) || is_int($charset)) {
            $this->charset = preg_split('/(?<!^)(?!$)/u', $charset);
            $this->validateCharset($this->charset);
        } elseif (is_array($charset)) {
            $this->charset = $charset;
            $this->validateCharset($this->charset);
        } else {
            $this->denyCharset();
        }
        $this->min = (int) $min;
        $length = count($this->charset);
        $this->max = null === $max ? $length : min((int) $max, $this->charset);
        $this->glue = $glue;
    }

    /**
     * @return NoDuplicateLettersStringCombinations
     */
    public function withoutDuplicates()
    {
        return new NoDuplicateLettersStringCombinations($this);
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
     * Creates a random string from current charset
     * @return string
     */
    public function getRandomString()
    {
        $length = random_int($this->min, $this->max);
        $charset = $this->charset;
        for ($pos = 0, $str = []; $pos < $length; $pos++) {
            shuffle($charset);
            $str[] = $charset[0];
        }
        return implode($this->glue, $str);
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
        if (null === $charset) {
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

    /**
     * @inheritDoc
     */
    public function __get($name)
    {
        return $this->{$name};
    }
}
