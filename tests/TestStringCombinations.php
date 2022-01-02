<?php

namespace BenTools\StringCombinations\Tests;

use function BenTools\StringCombinations\string_combinations;
use PHPUnit\Framework\TestCase;

class TestStringCombinations extends TestCase
{

    public function testWithDefaults(): void
    {
        $sc = string_combinations('abc');
        $expectedCount = 39; // (3^1) + (3^2) + (3^3)
        $expectedResult = [
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
        $this->assertCount($expectedCount, $sc);
        $result = $sc->asArray();
        $this->assertCount($expectedCount, $result);
        $this->assertCount($expectedCount, array_unique($result, SORT_STRING));
        $this->assertEquals($expectedResult, $result);
    }

    public function testWithMinValue(): void
    {
        $sc = string_combinations('abc', 2);
        $expectedCount = 36; // (3^2) + (3^3)
        $expectedResult = [
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
        $this->assertCount($expectedCount, $sc);
        $result = $sc->asArray();
        $this->assertCount($expectedCount, $result);
        $this->assertCount($expectedCount, array_unique($result, SORT_STRING));
        $this->assertEquals($expectedResult, $result);
    }

    public function testWithMinAndMaxValue(): void
    {
        $sc = string_combinations('abc', 2, 2);
        $expectedCount = 9; // (3^2)
        $expectedResult = [
            'aa',
            'ab',
            'ac',
            'ba',
            'bb',
            'bc',
            'ca',
            'cb',
            'cc',
        ];
        $this->assertCount($expectedCount, $sc);
        $result = $sc->asArray();
        $this->assertCount($expectedCount, $result);
        $this->assertCount($expectedCount, array_unique($result, SORT_STRING));
        $this->assertEquals($expectedResult, $result);
    }

    public function testWithArrayCharset(): void
    {
        $sc = string_combinations(['a1', 'b2', 'c3'], 2, 2, '-');
        $expectedCount = 9; // (3^2)
        $expectedResult = [
            'a1-a1',
            'a1-b2',
            'a1-c3',
            'b2-a1',
            'b2-b2',
            'b2-c3',
            'c3-a1',
            'c3-b2',
            'c3-c3',
        ];
        $this->assertCount($expectedCount, $sc);
        $result = $sc->asArray();
        $this->assertCount($expectedCount, $result);
        $this->assertCount($expectedCount, array_unique($result, SORT_STRING));
        $this->assertEquals($expectedResult, $result);
    }

    public function testInvalidCharset(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        string_combinations(new \stdClass());
    }

    public function testInvalidCharsetWithinArray(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        string_combinations([new \stdClass()]);
    }

    public function testRandomString(): void
    {
        $combinations = string_combinations('abcdef', 6, 6);
        $random = $combinations->getRandomString();
        $this->assertEquals(6, strlen($random));
        $this->assertNotEquals($random, $combinations->getRandomString());
    }

}
