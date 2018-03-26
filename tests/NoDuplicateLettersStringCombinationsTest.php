<?php

namespace BenTools\StringCombinations\Tests;

use BenTools\StringCombinations\NoDuplicateLettersStringCombinations;
use function BenTools\StringCombinations\string_combinations;
use PHPUnit\Framework\TestCase;

class NoDuplicateLettersStringCombinationsTest extends TestCase
{

    public function testCount()
    {
        $combinations = string_combinations('abcde')->withoutDuplicates();
        $this->assertCount(325, $combinations);
    }

    public function testGetRandomString()
    {
        $combinations = string_combinations('abc')->withoutDuplicates();

        for ($i = 0; $i <= 10000; $i++) {
            $string = str_split($combinations->getRandomString());
            $this->assertEquals($string, array_unique($string));
        }
    }

    public function testAsArray()
    {
        $combinations = string_combinations('abc');
        $expected = [
            'a',
            'b',
            'c',
            'aa',
            'ab',
            'ac',
            'ba',
            'bb',
            'bc',
            'ca',
            'cb',
            'cc',
            'aaa',
            'aab',
            'aac',
            'aba',
            'abb',
            'abc',
            'aca',
            'acb',
            'acc',
            'baa',
            'bab',
            'bac',
            'bba',
            'bbb',
            'bbc',
            'bca',
            'bcb',
            'bcc',
            'caa',
            'cab',
            'cac',
            'cba',
            'cbb',
            'cbc',
            'cca',
            'ccb',
            'ccc',
        ];
        $this->assertEquals($expected, $combinations->asArray());

        $combinations = $combinations->withoutDuplicates();
        $expected = [
            'a',
            'b',
            'c',
            'ab',
            'ac',
            'ba',
            'bc',
            'ca',
            'cb',
            'abc',
            'acb',
            'bac',
            'bca',
            'cab',
            'cba',
        ];
        $this->assertEquals($expected, $combinations->asArray());
    }
}
