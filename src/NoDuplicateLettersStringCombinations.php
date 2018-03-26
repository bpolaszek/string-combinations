<?php

namespace BenTools\StringCombinations;

use IteratorAggregate;

final class NoDuplicateLettersStringCombinations implements IteratorAggregate, StringCombinationsInterface
{
    /**
     * @var StringCombinations
     */
    private $stringCombinations;

    /**
     * UniqueStringCombinations constructor.
     */
    public function __construct(StringCombinations $stringCombinations)
    {
        $this->stringCombinations = $stringCombinations;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        for ($i = $this->stringCombinations->min; $i <= $this->stringCombinations->max; $i++) {
            foreach ($this->permute($this->stringCombinations->charset, $i) as $combination) {
                yield implode($this->stringCombinations->glue, $combination);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        $arr = [];

        for ($pos = $this->stringCombinations->max, $i = 0; $pos >= $this->stringCombinations->min; $pos--, $i++) {
            if (0 === $i) {
                $arr[$i] = [$pos];
            } else {
                $arr[$i] = array_merge($arr[$i - 1], [$pos]);
            }
        }

        return array_sum(array_map('array_product', $arr));
    }

    private function permute(array $charset, $length = null)
    {
        $n = count($charset);

        if (null === $length) {
            $length = $n;
        }

        if ($length > $n) {
            return;
        }

        $indices = range(0, $n - 1);
        $cycles = range($n, $n - $length + 1, -1);

        yield array_slice($charset, 0, $length);

        if ($n <= 0) {
            return;
        }

        while (true) {
            $exitEarly = false;
            for ($i = $length; $i--;) {
                $cycles[$i]-= 1;
                if ($cycles[$i] == 0) {
                    if ($i < count($indices)) {
                        $removed = array_splice($indices, $i, 1);
                        $indices[] = $removed[0];
                    }
                    $cycles[$i] = $n - $i;
                } else {
                    $j = $cycles[$i];
                    $value = $indices[$i];
                    $negative = $indices[count($indices) - $j];
                    $indices[$i] = $negative;
                    $indices[count($indices) - $j] = $value;
                    $result = [];
                    $counter = 0;
                    foreach ($indices as $index) {
                        $result[] = $charset[$index];
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
                break;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getRandomString()
    {
        $charset = $this->stringCombinations->charset;
        $string = [];

        $length = random_int($this->stringCombinations->min, $this->stringCombinations->max);

        for ($pos = 1; $pos <= $length; $pos++) {
            shuffle($charset);

            $string[] = array_shift($charset);
        }

        return implode($this->stringCombinations->glue, $string);
    }

    /**
     * @inheritDoc
     */
    public function asArray()
    {
        return iterator_to_array($this);
    }
}
