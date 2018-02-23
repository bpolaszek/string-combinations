<?php

namespace BenTools\StringCombinations;

use function BenTools\CartesianProduct\cartesian_product;

final class StringCombinations implements \IteratorAggregate, \Countable
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

    public function countUniques()
    {
        $result = 0;
        for ($i = ($this->max - 1); $i >= ($this->min - 1); $i--) {
            $result += fact($this->max) / fact($i);
        }
        return $result;
        return iterator_count($this->unDuplicateIterator());
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
     * @inheritDoc
     */
    public function unDuplicateIterator()
    {
        $permute = function(array $charset, $length = null) {
            $n = count($charset);

            if ($length == null) {
                $length = $n;
            }

            if ($length > $n) {
                return;
            }

            $indices = range(0, $n - 1);
            $cycles = range($n, $n - $length + 1, -1); // count down

            yield array_slice($charset, 0, $length);

            if ($n <= 0) {
                return;
            }

            while (true) {
                $exitEarly = false;
                for ($i = $length; $i--; $i >= 0) {
                    $cycles[$i]-= 1;
                    if ($cycles[$i] == 0) {
                        // Push whatever is at index $i to the end, move everything back
                        if ($i < count($indices)) {
                            $removed = array_splice($indices, $i, 1);
                            array_push($indices, $removed[0]);
                        }
                        $cycles[$i] = $n - $i;
                    } else {
                        $j = $cycles[$i];
                        // Swap indices $i & -$j.
                        $value = $indices[$i];
                        $negative = $indices[count($indices) - $j];
                        $indices[$i] = $negative;
                        $indices[count($indices) - $j] = $value;
                        $result = [];
                        $counter = 0;
                        foreach ($indices as $index) {
                            array_push($result, $charset[$index]);
                            $counter++;
                            if ($counter == $length) {
                                break;
                            }
                        }
                        yield $result;
                        $exitEarly = true;
                        break;
                    }
                }
                if (!$exitEarly) {
                    break; // Outer while loop
                }
            }
        };

        for ($i = $this->min; $i <= $this->max; $i++) {
            foreach ($permute($this->charset, $i) as $combination) {
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
        $length = mt_rand($this->min, $this->max);
        $charset = $this->charset;
        for ($pos = 0, $str = []; $pos < $length; $pos++) {
            shuffle($charset);
            $str[] = $charset[0];
        }
        return implode('', $str);
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
