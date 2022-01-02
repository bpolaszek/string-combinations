[![Latest Stable Version](https://poser.pugx.org/bentools/string-combinations/v/stable)](https://packagist.org/packages/bentools/string-combinations)
[![License](https://poser.pugx.org/bentools/string-combinations/license)](https://packagist.org/packages/bentools/string-combinations)
[![CI Workflow](https://github.com/bpolaszek/string-combinations/actions/workflows/ci-workflow.yml/badge.svg)](https://github.com/bpolaszek/string-combinations/actions/workflows/ci-workflow.yml)
[![Coverage](https://codecov.io/gh/bpolaszek/string-combinations/branch/master/graph/badge.svg?token=CF8DRI9XDW)](https://codecov.io/gh/bpolaszek/string-combinations)
[![Quality Score](https://img.shields.io/scrutinizer/g/bpolaszek/string-combinations.svg?style=flat-square)](https://scrutinizer-ci.com/g/bpolaszek/string-combinations)
[![Total Downloads](https://poser.pugx.org/bentools/string-combinations/downloads)](https://packagist.org/packages/bentools/string-combinations)

# String combinations

A simple, low-memory footprint function to generate all string combinations from a series of characters.

Installation
------------

PHP 7.4+ is required.

> composer require bentools/string-combinations

Usage
-----

I want to get all combinations with the letters `a`, `b`, `c`.

```php
require_once __DIR__ . '/vendor/autoload.php';
use function BenTools\StringCombinations\string_combinations;

foreach (string_combinations('abc') as $combination) { // Can also be string_combinations(['a', 'b', 'c'])
    echo $combination . PHP_EOL;
}
```

Output:
```
a
b
c
aa
ab
ac
ba
bb
bc
ca
cb
cc
aaa
aab
aac
aba
abb
abc
aca
acb
acc
baa
bab
bac
bba
bbb
bbc
bca
bcb
bcc
caa
cab
cac
cba
cbb
cbc
cca
ccb
ccc
```

**Array output**

_It will dump all combinations into an array._

```php
var_dump(string_combinations('abc')->asArray());
```

**Count combinations**

_It will return the number of possible combinations._

```php
var_dump(count(string_combinations('abc'))); // 39
```

**Specifying min length, max length**

```php
foreach (string_combinations('abc', $min = 2, $max = 2) as $combination) {
    echo $combination . PHP_EOL;
}
```

Output:
```
aa
ab
ac
ba
bb
bc
ca
cb
cc
```

**Using an array as first argument, and a separator**

```php
foreach (string_combinations(['woof', 'meow', 'roar'], 2, 3, '-') as $combination) {
    echo $combination . PHP_EOL;
}
```

Output:
```
woof-woof
woof-meow
woof-roar
meow-woof
meow-meow
meow-roar
roar-woof
roar-meow
roar-roar
woof-woof-woof
woof-woof-meow
woof-woof-roar
woof-meow-woof
woof-meow-meow
woof-meow-roar
woof-roar-woof
woof-roar-meow
woof-roar-roar
meow-woof-woof
meow-woof-meow
meow-woof-roar
meow-meow-woof
meow-meow-meow
meow-meow-roar
meow-roar-woof
meow-roar-meow
meow-roar-roar
roar-woof-woof
roar-woof-meow
roar-woof-roar
roar-meow-woof
roar-meow-meow
roar-meow-roar
roar-roar-woof
roar-roar-meow
roar-roar-roar
```

**No duplicates**

You can avoid generating stings having the same letter more than once with the `withoutDuplicates()` method:

```php
$combinations = string_combinations('abc');
var_dump(count($combinations)); // 39
print_r($combinations->asArray());
```

> Array
  (
      [0] => a
      [1] => b
      [2] => c
      [3] => aa
      [4] => ab
      [5] => ac
      [6] => ba
      [7] => bb
      [8] => bc
      [9] => ca
      [10] => cb
      [11] => cc
      [12] => aaa
      [13] => aab
      [14] => aac
      [15] => aba
      [16] => abb
      [17] => abc
      [18] => aca
      [19] => acb
      [20] => acc
      [21] => baa
      [22] => bab
      [23] => bac
      [24] => bba
      [25] => bbb
      [26] => bbc
      [27] => bca
      [28] => bcb
      [29] => bcc
      [30] => caa
      [31] => cab
      [32] => cac
      [33] => cba
      [34] => cbb
      [35] => cbc
      [36] => cca
      [37] => ccb
      [38] => ccc
  )


```php
$combinations = $combinations->withoutDuplicates();
var_dump(count($combinations)); // 15
print_r($combinations->asArray());
```

> Array
  (
      [0] => a
      [1] => b
      [2] => c
      [3] => ab
      [4] => ac
      [5] => ba
      [6] => bc
      [7] => ca
      [8] => cb
      [9] => abc
      [10] => acb
      [11] => bac
      [12] => bca
      [13] => cab
      [14] => cba
  )


Performance considerations
--------------------------

Because it uses [Generators](http://php.net/manual/en/language.generators.syntax.php) to generate all possible combinations, you can iterate over thousands of possibilities without losing a single MB.

This has been executed on my Core i7 personnal computer with 8GB RAM:
```php
require_once __DIR__ . '/vendor/autoload.php';
use function BenTools\StringCombinations\string_combinations;

$start = microtime(true);
$combinations = string_combinations('abcd1234');
foreach ($combinations as $c => $combination) {
    continue;
}
$end = microtime(true);

printf(
    'Generated %d combinations in %ss - Memory usage: %sMB / Peak usage: %sMB' . PHP_EOL,
    ++$c,
    round($end - $start, 3),
    round(memory_get_usage(true) / 1024 / 1024),
    round(memory_get_peak_usage(true) / 1024 / 1024)
);
```

Output:
> Generated 19173960 combinations in 5.579s - Memory usage: 2MB / Peak usage: 2MB


Tests
------------

> ./vendor/bin/phpunit


See also
--------

- [bentools/cartesian-product](https://github.com/bpolaszek/cartesian-product)
- [bentools/iterable-functions](https://github.com/bpolaszek/php-iterable-functions)
